<?php
namespace App\Http\Controllers;
use App\Models\Doctor;
use Illuminate\Http\Request;
class DoctorController extends Controller
{
    public function index(Request $r)
    {
        $q = Doctor::query();
        if ($r->search)
            $q->where(fn($x) => $x->where('first_name', 'like', '%' . $r->search . '%')->orWhere('last_name', 'like', '%' . $r->search . '%')->orWhere('specialization', 'like', '%' . $r->search . '%'));
        $sort = $r->sort === 'name' ? 'first_name' : 'created_at';
        return view('doctors.index', ['doctors' => $q->orderBy($sort, $r->sort === 'oldest' ? 'asc' : 'desc')->paginate(10)->withQueryString()]);
    }
    public function create()
    {
        return view('doctors.create');
    }
    public function store(Request $r)
    {
        $data = $r->validate(['first_name' => 'required', 'last_name' => 'required', 'specialization' => 'required', 'phone' => 'required', 'email' => 'nullable|email']);
        Doctor::create($data);
        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }
    public function show(Doctor $doctor)
    {
        $doctor->load('appointments.patient');
        return view('doctors.show', compact('doctor'));
    }
    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }
    public function update(Request $r, Doctor $doctor)
    {
        $data = $r->validate(['first_name' => 'required', 'last_name' => 'required', 'specialization' => 'required', 'phone' => 'required', 'email' => 'nullable|email']);
        $doctor->update($data);
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }
    public function destroy(Doctor $doctor)
    {
        if ($doctor->appointments()->exists())
            return back()->with('error', 'This doctor has appointments and cannot be deleted.');
        $doctor->delete();
        return back()->with('success', 'Doctor deleted successfully.');
    }
}