<?php
namespace App\Http\Controllers;
use App\Models\Patient; use Illuminate\Http\Request;
class PatientController extends Controller {
 public function index(Request $r){$q=Patient::query();if($r->search)$q->where(fn($x)=>$x->where('first_name','like','%'.$r->search.'%')->orWhere('last_name','like','%'.$r->search.'%')->orWhere('phone','like','%'.$r->search.'%'));$sort=$r->sort==='oldest'?'created_at':($r->sort==='name'?'first_name':'created_at');return view('patients.index',['patients'=>$q->orderBy($sort,$r->sort==='oldest'?'asc':'desc')->paginate(10)->withQueryString()]);}
 public function create(){return view('patients.create');}
 public function store(Request $r){$data=$r->validate(['first_name'=>'required','last_name'=>'required','phone'=>'required','email'=>'nullable|email','date_of_birth'=>'nullable|date','gender'=>'required|in:male,female,other','address'=>'nullable','emergency_contact'=>'nullable']);Patient::create($data);return redirect()->route('patients.index')->with('success','Patient created successfully.');}
 public function show(Patient $patient){$patient->load('appointments.doctor','appointments.medicalRecord');return view('patients.show',compact('patient'));}
 public function edit(Patient $patient){return view('patients.edit',compact('patient'));}
 public function update(Request $r,Patient $patient){$data=$r->validate(['first_name'=>'required','last_name'=>'required','phone'=>'required','email'=>'nullable|email','date_of_birth'=>'nullable|date','gender'=>'required|in:male,female,other','address'=>'nullable','emergency_contact'=>'nullable']);$patient->update($data);return redirect()->route('patients.index')->with('success','Patient updated successfully.');}
 public function destroy(Patient $patient){if($patient->appointments()->exists())return back()->with('error','This patient has appointments and cannot be deleted.');$patient->delete();return back()->with('success','Patient deleted successfully.');}
 public function doctorPatients(Request $request){$query=Patient::whereHas('appointments',fn($q)=>$q->where('doctor_id',auth()->user()->doctor->id));if($request->search)$query->where(fn($q)=>$q->where('first_name','like','%'.$request->search.'%')->orWhere('last_name','like','%'.$request->search.'%'));return view('doctors.patients.index',['patients'=>$query->orderBy('first_name')->paginate(12)->withQueryString()]);}
 public function doctorPatient(Patient $patient){if(!$patient->appointments()->where('doctor_id',auth()->user()->doctor->id)->exists())abort(403);$patient->load(['appointments'=>fn($q)=>$q->where('doctor_id',auth()->user()->doctor->id)->with(['doctor','medicalRecord'])->latest('appointment_date')]);return view('doctors.patients.show',compact('patient'));}
}
