@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <div>

            <h1 class="mb-1">Welcome, {{ $patient ? $patient->first_name : auth()->user()->name }}</h1>

        </div>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">+ Book appointment</a>
    </div>

    <div class="row g-3 mb-4 stats-row">
        <div class="col-sm-6 col-xl-4">
            <x-stat-card label="Upcoming appointments" :value="$upcomingAppointments" color="primary" icon="▣" />
        </div>
        <div class="col-sm-6 col-xl-4">
            <x-stat-card label="Completed visits" :value="$completedAppointments" color="success" icon="✓" />
        </div>
        <div class="col-sm-6 col-xl-4">
            <x-stat-card label="Medical records" :value="$medicalRecords" color="info" icon="▤" />
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">My appointments</h2>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}<br><small
                                            class="text-muted">{{ substr($appointment->appointment_time, 0, 5) }}</small></td>
                                    <td>Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                                    <td>
                                        <x-status-badge :status="$appointment->status" />
                                    </td>
                                    <td><a href="{{ route('appointments.show', $appointment) }}"
                                            class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No appointments booked.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
@endsection