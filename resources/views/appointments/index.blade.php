
@extends('layouts.app') @section('content')<div class="d-flex justify-content-between mb-3">
<h1>Appointments</h1>
<a class="btn btn-primary" href="{{ route('appointments.create') }}">+ Create Appointment</a>
</div>
<form class="row g-2 mb-3">
<div class="col-md-3">
<input type="date" name="date" class="form-control" value="{{ request('date') }}">
</div>
<div class="col-md-3">
<select name="status" class="form-select">
<option value="">All statuses</option>
@foreach(['pending','confirmed','completed','cancelled'] as $s)<option @selected(request('status')===$s)>{{ $s }}</option>
@endforeach</select>
</div>
<div class="col-md-3">
<select name="sort" class="form-select">
<option value="date">Date</option>
<option value="status">Status</option>
<option value="doctor">Doctor</option>
</select>
</div>
<div class="col-md-2">
<button class="btn btn-outline-primary">Filter</button>
</div>
</form>
<div class="card p-3 table-responsive">
<table class="table">
<tr>
<th>ID</th>
<th>Patient</th>
<th>Doctor</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Actions</th>
</tr>
@forelse($appointments as $a)<tr>
<td>{{ $a->id }}</td>
<td>{{ $a->patient->first_name }} {{ $a->patient->last_name }}</td>
<td>Dr. {{ $a->doctor->first_name }} {{ $a->doctor->last_name }}</td>
<td>{{ $a->appointment_date->format('Y-m-d') }}</td>
<td>{{ substr($a->appointment_time,0,5) }}</td>
<td>
<x-status-badge :status="$a->status" />
</td>
<td>
<a href="{{ route('appointments.show',$a) }}" class="btn btn-sm btn-info">View</a>
@if(auth()->user()->role === 'admin')
<a href="{{ route('appointments.edit',$a) }}" class="btn btn-sm btn-warning">Edit</a>
<form class="d-inline" method="POST" action="{{ route('appointments.destroy',$a) }}">
@csrf @method('DELETE')<button onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-sm btn-danger">Delete</button>
</form>
@elseif(auth()->user()->role === 'doctor' && in_array($a->status,['pending','confirmed']))
<form class="d-inline" method="POST" action="{{ route('appointments.start',$a) }}">
@csrf
<button class="btn btn-sm btn-success">Start</button>
</form>
@endif
</td>
</tr>
@empty<tr>
<td colspan="7">No appointments found.</td>
</tr>
@endforelse</table>{{ $appointments->links() }}</div>
@endsection

