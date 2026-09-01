@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div>

        <h1 class="mb-1">My availability</h1>

    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <x-stat-card label="Total slots" :value="$slots->count()" color="primary" icon="◷" />
    </div>
    <div class="col-sm-6 col-lg-4">
        <x-stat-card label="Available" :value="$slots->where('is_booked', false)->count()" color="success" icon="✓" />
    </div>
    <div class="col-sm-6 col-lg-4">
        <x-stat-card label="Booked" :value="$slots->where('is_booked', true)->count()" color="warning" icon="▣" />
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h5 mb-0">Add working period</h2>
                <span class="badge bg-primary-subtle text-primary">30 min default</span>
            </div>

            <form method="POST" action="{{ route('availability.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="available_date">Date</label>
                    <input id="available_date" type="date" name="available_date" class="form-control"
                        min="{{ date('Y-m-d') }}" value="{{ old('available_date') }}" required>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label" for="start_time">Start time</label>
                        <input id="start_time" type="time" name="start_time" class="form-control"
                            value="{{ old('start_time', '10:00') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="end_time">End time</label>
                        <input id="end_time" type="time" name="end_time" class="form-control"
                            value="{{ old('end_time', '12:00') }}" required>
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <label class="form-label" for="slot_duration">Slot duration</label>
                    <select id="slot_duration" name="slot_duration" class="form-select">
                        <option value="30" @selected(old('slot_duration', '30') === '30')>30 minutes</option>
                        <option value="15" @selected(old('slot_duration') === '15')>15 minutes</option>
                        <option value="60" @selected(old('slot_duration') === '60')>60 minutes</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100">Generate slots</button>
            </form>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Upcoming slots</h2>
                <span class="text-muted small">{{ $slots->count() }} total</span>
            </div>

            @php
                $slotsByDate = $slots->groupBy(fn($slot) => $slot->available_date->format('Y-m-d'));
            @endphp

            <div class="availability-days">
                @forelse ($slotsByDate as $date => $dateSlots)
                @php($dayId = 'day-' . str_replace('-', '', $date))
                <div class="availability-day">
                    <button class="availability-day-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#{{ $dayId }}">
                        <span>
                            <strong>{{ \Carbon\Carbon::parse($date)->format('d') }}</strong>
                            <span>{{ \Carbon\Carbon::parse($date)->format('M Y') }}</span>
                        </span>
                        <span class="d-flex align-items-center gap-3">
                            <small>{{ $dateSlots->count() }} slots</small>
                            <span class="availability-chevron">⌄</span>
                        </span>
                    </button>

                    <div id="{{ $dayId }}" class="collapse">
                        <div class="availability-slot-list">
                            @foreach ($dateSlots as $slot)
                                <div class="availability-slot">
                                    <span class="fw-semibold">{{ substr($slot->available_time, 0, 5) }}</span>
                                    <span
                                        class="badge {{ $slot->is_booked ? 'text-bg-secondary' : 'bg-success-subtle text-success' }}">
                                        {{ $slot->is_booked ? 'Booked' : 'Available' }}
                                    </span>
                                    @if (!$slot->is_booked)
                                        <form method="POST" action="{{ route('availability.destroy', $slot) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-link text-danger p-0"
                                                onclick="return confirm('Remove this slot?')">Remove</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-muted py-3">No upcoming slots.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection