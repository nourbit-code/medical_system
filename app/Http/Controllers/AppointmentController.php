<?php
namespace App\Http\Controllers;
use App\Models\{Appointment, Patient, Doctor, DoctorAvailability};
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);
        if (auth()->user()->role === 'patient')
            $query->where('patient_id', auth()->user()->patient->id);
        if (auth()->user()->role === 'doctor')
            $query->where('doctor_id', auth()->user()->doctor->id);
        if ($request->date)
            $query->whereDate('appointment_date', $request->date);
        if ($request->status)
            $query->where('status', $request->status);
        return view('appointments.index', ['appointments' => $query->latest('appointment_date')->paginate(10)->withQueryString()]);
    }

    public function create(Request $request)
    {
        $slotQuery = function ($query) {
            $query->where('is_booked', false)->where('available_date', '>=', today())->orderBy('available_date')->orderBy('available_time'); };
        $user = auth()->user();
        $doctorsQuery = Doctor::with(['availabilities' => $slotQuery])->whereHas('availabilities', $slotQuery);
        if ($user->role === 'doctor')
            $doctorsQuery->where('id', optional($user->doctor)->id);
        $doctors = $doctorsQuery->orderBy('first_name')->get();
        $selectedDoctor = $user->role === 'doctor'
            ? $doctors->first()
            : ($request->doctor_id ? $doctors->firstWhere('id', (int) $request->doctor_id) : null);
        return view('appointments.create', ['patients' => auth()->user()->role === 'patient' ? [auth()->user()->patient] : Patient::orderBy('first_name')->get(), 'doctors' => $doctors, 'selectedDoctor' => $selectedDoctor]);
    }

    public function store(Request $request)
    {
        $rules = ['patient_id' => 'required|exists:patients,id', 'doctor_id' => 'nullable|exists:doctors,id', 'availability_id' => 'nullable|exists:doctor_availabilities,id', 'appointment_date' => 'required_without:availability_id|date', 'appointment_time' => 'required_without:availability_id', 'reason' => 'nullable', 'status' => 'nullable|in:pending,confirmed,completed,cancelled'];
        if (in_array(auth()->user()->role, ['patient', 'doctor']))
            $rules['availability_id'] = 'required|exists:doctor_availabilities,id';
        $data = $request->validate($rules);
        if (auth()->user()->role === 'patient') {
            $data['patient_id'] = auth()->user()->patient->id;
            $slot = DoctorAvailability::where('id', $data['availability_id'])->where('is_booked', false)->firstOrFail();
            $data['doctor_id'] = $slot->doctor_id;
            $data['appointment_date'] = $slot->available_date;
            $data['appointment_time'] = $slot->available_time;
        } elseif (auth()->user()->role === 'doctor') {
            $slot = DoctorAvailability::where('id', $data['availability_id'])->where('doctor_id', auth()->user()->doctor->id)->where('is_booked', false)->firstOrFail();
            $data['doctor_id'] = auth()->user()->doctor->id;
            $data['appointment_date'] = $slot->available_date;
            $data['appointment_time'] = $slot->available_time;
        } else {
            $slot = null;
        }
        $data['status'] = $data['status'] ?? 'pending';
        $appointment = Appointment::create($data);
        if ($slot)
            $slot->update(['is_booked' => true]);
        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->checkAccess($appointment);
        $appointment->load(['patient', 'doctor', 'medicalRecord']);
        return view('appointments.show', compact('appointment'));
    }
    public function start(Appointment $appointment)
    {
        $this->checkAccess($appointment);
        if (auth()->user()->role !== 'doctor' || !in_array($appointment->status, ['pending', 'confirmed']))
            abort(403);
        $appointment->update(['status' => 'in_progress']);
        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment started. You can now complete the patient EMR.');
    }
    public function edit(Appointment $appointment)
    {
        $this->checkAccess($appointment);
        if (auth()->user()->role === 'patient')
            abort(403);
        return view('appointments.edit', ['appointment' => $appointment, 'patients' => Patient::orderBy('first_name')->get(), 'doctors' => Doctor::orderBy('first_name')->get()]);
    }
    public function update(Request $request, Appointment $appointment)
    {
        $this->checkAccess($appointment);
        $data = $request->validate(['patient_id' => 'required|exists:patients,id', 'doctor_id' => 'required|exists:doctors,id', 'appointment_date' => 'required|date', 'appointment_time' => 'required', 'reason' => 'nullable', 'status' => 'required|in:pending,confirmed,completed,cancelled']);
        if (auth()->user()->role === 'doctor')
            $data = ['status' => $data['status'], 'reason' => $data['reason'] ?? $appointment->reason];
        $appointment->update($data);
        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully.');
    }
    public function destroy(Appointment $appointment)
    {
        if (auth()->user()->role !== 'admin')
            abort(403);
        $appointment->delete();
        return back()->with('success', 'Appointment deleted successfully.');
    }
    private function checkAccess($appointment)
    {
        $user = auth()->user();
        if ($user->role === 'patient' && $appointment->patient_id !== optional($user->patient)->id)
            abort(403);
        if ($user->role === 'doctor' && $appointment->doctor_id !== optional($user->doctor)->id)
            abort(403);
    }
}