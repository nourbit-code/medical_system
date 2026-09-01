@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>

            <h1 class="mb-1">Good day, {{ auth()->user()->name }}</h1>

        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Create account</a>
    </div>

    <div class="row g-3 mb-4 stats-row">
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Total patients" :value="$patients" color="primary" icon="♧" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Total doctors" :value="$doctors" color="success" icon="✚" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Today's appointments" :value="$todayAppointments" color="warning" icon="▣" />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-stat-card label="Completed appointments" :value="$completed" color="info" icon="✓" />
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Today's appointments</h2>
            <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td>{{ substr($appointment->appointment_time, 0, 5) }}</td>
                            <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                            <td>Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                            <td>
                                <x-status-badge :status="$appointment->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No appointments today.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection