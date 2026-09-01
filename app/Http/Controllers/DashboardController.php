<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'doctor') {
            return $this->doctorDashboard($user);
        }

        if ($user->role === 'patient') {
            return $this->patientDashboard($user);
        }

        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        $today = Carbon::today();

        return view('dashboard.index', [
            'patients' => Patient::count(),
            'doctors' => Doctor::count(),
            'todayAppointments' => Appointment::whereDate('appointment_date', $today)->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'appointments' => Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_date', $today)
                ->orderBy('appointment_time')
                ->get(),
        ]);
    }

    private function doctorDashboard($user)
    {
        $doctor = $user->doctor;

        if (!$doctor) {
            return view('dashboard.doctor', [
                'doctor' => null,
                'appointments' => collect(),
                'todayAppointments' => 0,
                'upcomingAppointments' => 0,
                'completedAppointments' => 0,
                'patientCount' => 0,
            ]);
        }

        $appointments = $doctor->appointments()
            ->with(['patient', 'medicalRecord'])
            ->whereDate('appointment_date', '>=', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(8)
            ->get();

        return view('dashboard.doctor', [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'todayAppointments' => $doctor->appointments()->whereDate('appointment_date', today())->count(),
            'upcomingAppointments' => $doctor->appointments()
                ->whereDate('appointment_date', '>=', today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'completedAppointments' => $doctor->appointments()->where('status', 'completed')->count(),
            'patientCount' => $doctor->appointments()->distinct('patient_id')->count('patient_id'),
        ]);
    }

    private function patientDashboard($user)
    {
        $patient = $user->patient;

        if (!$patient) {
            return view('dashboard.patient', [
                'patient' => null,
                'appointments' => collect(),
                'upcomingAppointments' => 0,
                'completedAppointments' => 0,
                'medicalRecords' => 0,
            ]);
        }

        $appointments = $patient->appointments()
            ->with(['doctor', 'medicalRecord'])
            ->whereDate('appointment_date', '>=', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(8)
            ->get();

        return view('dashboard.patient', [
            'patient' => $patient,
            'appointments' => $appointments,
            'upcomingAppointments' => $patient->appointments()
                ->whereDate('appointment_date', '>=', today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'completedAppointments' => $patient->appointments()->where('status', 'completed')->count(),
            'medicalRecords' => $patient->appointments()->whereHas('medicalRecord')->count(),
        ]);
    }
}