<?php
namespace App\Http\Controllers;

use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $slots = $doctor->availabilities()->where('available_date','>=',today())->orderBy('available_date')->orderBy('available_time')->get();
        return view('availability.index', compact('doctor','slots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'available_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration' => 'required|in:15,30,60',
        ]);

        $start = Carbon::createFromFormat('H:i', $data['start_time']);
        $end = Carbon::createFromFormat('H:i', $data['end_time']);

        if ($end->lessThanOrEqualTo($start)) return back()->withInput()->with('error','End time must be after start time.');

        $doctor = auth()->user()->doctor;
        $created = 0;
        while ($start->copy()->addMinutes((int) $data['slot_duration'])->lessThanOrEqualTo($end)) {
            $exists = $doctor->availabilities()->where('available_date',$data['available_date'])->where('available_time',$start->format('H:i:s'))->exists();
            if (!$exists) { $doctor->availabilities()->create(['available_date'=>$data['available_date'],'available_time'=>$start->format('H:i:s')]); $created++; }
            $start->addMinutes((int) $data['slot_duration']);
        }

        if ($created === 0) return back()->withInput()->with('error','All slots in this period already exist.');
        return back()->with('success',$created.' appointment slots created successfully.');
    }

    public function destroy(DoctorAvailability $availability)
    {
        if ($availability->doctor_id !== auth()->user()->doctor->id || $availability->is_booked) abort(403);
        $availability->delete();
        return back()->with('success','Available slot removed.');
    }
}
