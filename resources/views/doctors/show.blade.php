
@extends('layouts.app') @section('content')<h1>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h1>
<div class="card p-4 mb-4">
<p>
<strong>Specialization:</strong> {{ $doctor->specialization }}</p>
<p>
<strong>Phone:</strong> {{ $doctor->phone }}</p>
<p>
<strong>Email:</strong> {{ $doctor->email ?: '-' }}</p>
</div>
<div class="card p-4">
<h4>Appointments</h4>
<table class="table">
<tr>
<th>Date</th>
<th>Patient</th>
<th>Status</th>
</tr>
@forelse($doctor->appointments as $a)<tr>
<td>{{ $a->appointment_date->format('Y-m-d') }}</td>
<td>{{ $a->patient->first_name }} {{ $a->patient->last_name }}</td>
<td><x-status-badge :status="$a->status" /></td>
</tr>
@empty<tr>
<td colspan="3">No appointments.</td>
</tr>
@endforelse</table>
</div>
@endsection

