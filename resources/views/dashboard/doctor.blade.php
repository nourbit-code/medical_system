@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <div>

            <h1 class="mb-1">Good day, Dr. {{ $doctor->last_name ?? auth()->user()->name }}</h1>

        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('availability.index') }}" class="btn btn-outline-primary">Manage availability</a>
            <a href="{{ route('doctors.patients.index') }}" class="btn btn-primary">My patients</a>
        </div>
    </div>

    <div class="row g-3 mb-4 stats-row">
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Today's appointments" :value="$todayAppointments" color="primary" icon="▣" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Upcoming visits" :value="$upcomingAppointments" color="info" icon="◷" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="My patients" :value="$patientCount" color="success" icon="♧" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Completed EMRs" :value="$completedAppointments" color="warning" icon="✓" />
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Upcoming appointments</h2>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}<br><small
                                            class="text-muted">{{ substr($appointment->appointment_time, 0, 5) }}</small></td>
                                    <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                    <td>
                                        <x-status-badge :status="$appointment->status" />
                                    </td>
                                    <td>
                                        @if (in_array($appointment->status, ['pending', 'confirmed']))
                                            <form method="POST" action="{{ route('appointments.start', $appointment) }}">
                                                @csrf<button class="btn btn-sm btn-primary">Start</button></form>
                                        @elseif ($appointment->status === 'in_progress')
                                            <a href="{{ route('medical-records.create', $appointment) }}"
                                                class="btn btn-sm btn-success">Open EMR</a>
                                        @else
                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                class="btn btn-sm btn-outline-primary">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No upcoming appointments.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
@endsection