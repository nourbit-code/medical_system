@extends('layouts.app')

@section('content')
    <div class="page-heading patient-page-heading">
        <div>
            <a href="{{ route('doctors.patients.index') }}" class="back-link">← Back to patients</a>

            <div class="d-flex align-items-center gap-3">
                <div class="profile-avatar profile-avatar-small">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h1>

                </div>
            </div>
        </div>
        <span class="profile-visit-count">{{ $patient->appointments->count() }} visits</span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-7">
            <div class="card p-4 h-100">
                <div class="section-heading mb-4">
                    <div>
                        <p class="eyebrow mb-1">PATIENT DETAILS</p>

                    </div>
                </div>

                <dl class="profile-details patient-details-grid mb-0">
                    <div>
                        <dt>Full name</dt>
                        <dd>{{ $patient->first_name }} {{ $patient->last_name }}</dd>
                    </div>
                    <div>
                        <dt>Age</dt>
                        <dd>{{ $patient->age !== null ? $patient->age . ' years' : 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt>Date of birth</dt>
                        <dd>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt>Phone</dt>
                        <dd>{{ $patient->phone ?: 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $patient->email ?: 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt>Address</dt>
                        <dd>{{ $patient->address ?: 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt>Emergency contact</dt>
                        <dd>{{ $patient->emergency_contact ?: 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt>Gender</dt>
                        <dd>{{ ucfirst($patient->gender) }}</dd>
                    </div>

                </dl>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card p-4 h-100 patient-care-card">
                <p class="eyebrow mb-1">Patient care</p>

                <div class="care-list">
                    <div><span>Appointments</span><strong>{{ $patient->appointments->count() }}</strong></div>
                    <div><span>Completed
                            visits</span><strong>{{ $patient->appointments->where('status', 'completed')->count() }}</strong>
                    </div>
                    <div><span>Latest
                            visit</span><strong>{{ $patient->appointments->first() ? $patient->appointments->first()->appointment_date->format('M d, Y') : 'No visits yet' }}</strong>
                    </div>
                </div>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary mt-4">Create appointment</a>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="section-heading mb-4">
            <div>

                <h2 class="h4 mb-0">CLINICAL TIMELINE</h2>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table patient-history-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Visit</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Prescription</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patient->appointments as $appointment)
                        <tr>
                            <td>
                                <a href="{{ route('appointments.show', $appointment) }}" class="visit-link">
                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                    <small>{{ substr($appointment->appointment_time, 0, 5) }}</small>
                                </a>
                            </td>
                            <td>Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                            <td>
                                <x-status-badge :status="$appointment->status" />
                            </td>
                            <td>{{ $appointment->medicalRecord->diagnosis ?? '-' }}</td>
                            <td>{{ $appointment->medicalRecord->treatment ?? '-' }}</td>
                            <td class="prescription-preview">{{ $appointment->medicalRecord->prescription ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon">▤</div>
                                    <strong>No visits recorded yet.</strong>
                                    <span>The patient's appointments and EMR history will appear here.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection