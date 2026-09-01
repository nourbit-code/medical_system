@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <div>

            <h1 class="mb-1">Appointment #{{ $appointment->id }}</h1>

        </div>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Back to appointments</a>
    </div>

    <div class="card p-4 mb-4 appointment-details-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-avatar profile-avatar-small">
                    {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-muted small"></div>
                    <h2 class="h4 mb-0">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h2>
                </div>
            </div>
            <x-status-badge :status="$appointment->status" />
        </div>

        <div class="appointment-meta-grid">
            <div class="appointment-meta">
                <span class="meta-label">Doctor</span>
                <strong>Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</strong>
            </div>
            <div class="appointment-meta">
                <span class="meta-label">Date and time</span>
                <strong>{{ $appointment->appointment_date->format('M d, Y') }} at
                    {{ substr($appointment->appointment_time, 0, 5) }}</strong>
            </div>
            <div class="appointment-meta">
                <span class="meta-label">Patient age</span>
                <strong>{{ $appointment->patient->age !== null ? $appointment->patient->age . ' years' : 'Not provided' }}</strong>
            </div>
            <div class="appointment-meta">
                <span class="meta-label">Reason</span>
                <strong>{{ $appointment->reason ?: 'Not provided' }}</strong>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            @if (auth()->user()->role === 'doctor' && in_array($appointment->status, ['pending', 'confirmed']))
                <form method="POST" action="{{ route('appointments.start', $appointment) }}">
                    @csrf
                    <button class="btn btn-primary">Start appointment</button>
                </form>
            @elseif (auth()->user()->role === 'doctor' && $appointment->status === 'in_progress')
                <a href="{{ route('medical-records.create', $appointment) }}" class="btn btn-success">Open EMR</a>
            @endif

            @if (auth()->user()->role === 'doctor')
                <a href="{{ route('doctors.patients.show', $appointment->patient) }}" class="btn btn-outline-primary">Patient
                    history</a>
            @endif

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-primary">Edit appointment</a>
            @endif
        </div>

        <hr class="my-4">

        <div class="mb-3">


        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="contact-item">
                    <span class="meta-label">Phone</span>
                    <strong>{{ $appointment->patient->phone ?: 'Not provided' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-item">
                    <span class="meta-label">Email</span>
                    <strong>{{ $appointment->patient->email ?: 'Not provided' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-item">
                    <span class="meta-label">Gender</span>
                    <strong>{{ ucfirst($appointment->patient->gender) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 appointment-details-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <p class="eyebrow mb-1">CLINICAL RECORD</p>

            </div>
            @if (
                    $appointment->medicalRecord && $appointment->status === 'completed' && in_array(
                        auth()->user()->role,
                        ['admin', 'doctor']
                    )
                )
                <a href="{{ route('medical-records.create', $appointment) }}" class="btn btn-sm btn-outline-primary">Update
                    EMR</a>
            @endif
        </div>

        @if ($appointment->medicalRecord)
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="emr-field">
                        <span class="meta-label">Diagnosis</span>
                        <div>{{ $appointment->medicalRecord->diagnosis ?: '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="emr-field">
                        <span class="meta-label">Treatment plan</span>
                        <div>{{ $appointment->medicalRecord->treatment ?: '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="emr-field">
                        <span class="meta-label">Prescription</span>
                        @if ($appointment->medicalRecord->prescription)
                            @foreach (explode("\n", $appointment->medicalRecord->prescription) as $prescription)
                                <div class="prescription-line">{{ $prescription }}</div>
                            @endforeach
                        @else
                            <div>-</div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">

                <strong>No EMR has been added yet.</strong>

            </div>
        @endif
    </div>
@endsection