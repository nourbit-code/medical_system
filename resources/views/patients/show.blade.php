@extends('layouts.app')

@section('content')
<a href="{{ route('patients.index') }}" class="back-link">← Back to patients</a>



<div class="profile-summary card p-4 mb-4">
    <div class="d-flex flex-wrap align-items-center gap-3">
        <div class="profile-avatar">
            {{ strtoupper(substr($patient->first_name, 0, 1)) }}
        </div>
        <div class="flex-grow-1">
            <h2 class="h4 mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
            <div class="text-muted">{{ $patient->email ?: 'No email provided' }}</div>
        </div>
        <div class="profile-contact">
            <span>☎</span> {{ $patient->phone }}
        </div>
        <div class="profile-contact">
            <span>◷</span> {{ $patient->age !== null ? $patient->age . ' years' : 'Age not provided' }}
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <h2 class="h5 mb-4">Personal information</h2>
            <dl class="profile-details mb-0">
                <div>
                    <dt>Full name</dt>
                    <dd>{{ $patient->first_name }} {{ $patient->last_name }}</dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd>{{ $patient->email ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Phone</dt>
                    <dd>{{ $patient->phone }}</dd>
                </div>
                <div>
                    <dt>Address</dt>
                    <dd>{{ $patient->address ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Date of birth</dt>
                    <dd>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : '-' }}</dd>
                </div>
                <div>
                    <dt>Gender</dt>
                    <dd>{{ ucfirst($patient->gender) }}</dd>
                </div>
                <div>
                    <dt>Emergency contact</dt>
                    <dd>{{ $patient->emergency_contact ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Recent appointments</h2>
                <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary">New appointment</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>EMR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patient->appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date->format('M d, Y') }}<br><small
                                    class="text-muted">{{ substr($appointment->appointment_time, 0, 5) }}</small></td>
                            <td>Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                            <td><span <x-status-badge :status="$appointment->status" />
                            </td>
                            <td><a href="{{ route('appointments.show', $appointment) }}"
                                    class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted">No appointments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
