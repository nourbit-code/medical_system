@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">APPOINTMENT MANAGEMENT</p>
            <h1 class="mb-1">Edit appointment</h1>
            <p class="text-muted mb-0">Update the visit details and current status.</p>
        </div>
        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">Back to details</a>
    </div>

    <div class="card appointment-form-card p-4 p-lg-5">
        @include('appointments.form', [
            'action' => route('appointments.update', $appointment),
            'method' => 'PUT',
        ])
    </div>
@endsection
