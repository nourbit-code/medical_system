@if ($errors->any())
<div class="alert alert-danger">
    <strong>Please fix these items:</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (in_array(auth()->user()->role, ['patient', 'doctor']) && request()->routeIs('appointments.create'))
@if (auth()->user()->role === 'patient')
<div class="card border-0 bg-light p-3 mb-4">
    <form method="GET" action="{{ route('appointments.create') }}">
        <label class="form-label fw-semibold" for="doctor_id">1. Choose a doctor</label>
        <div class="input-group">
            <select id="doctor_id" name="doctor_id" class="form-select" required>
                <option value="">Select doctor</option>
                @foreach ($doctors as $doctor)
                <option value="{{ $doctor->id }}" @selected(optional($selectedDoctor)->id === $doctor->id)>
                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} — {{ $doctor->specialization }}
                </option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary">Show available slots</button>
        </div>
    </form>
</div>
@else
@if ($selectedDoctor)
<div class="alert alert-primary d-flex align-items-center gap-2">
    <strong>Doctor schedule:</strong>
    Dr. {{ $selectedDoctor->first_name }} {{ $selectedDoctor->last_name }}
</div>
@else
<div class="alert alert-warning">No available slots have been created for your account.</div>
@endif
@endif

@if ($selectedDoctor)
<div class="card p-4">
    <form method="POST" action="{{ $action }}">
        @csrf
        @method($method)
        @if (auth()->user()->role === 'patient')
        <input type="hidden" name="patient_id" value="{{ auth()->user()->patient->id }}">
        @else
        <div class="mb-3">
            <label class="form-label fw-semibold" for="patient_id">Patient</label>
            <select id="patient_id" name="patient_id" class="form-select" required>
                <option value="">Select patient</option>
                @foreach ($patients as $patient)
                <option value="{{ $patient->id }}" @selected(old('patient_id')==$patient->id)>
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif
        <input type="hidden" name="doctor_id" value="{{ $selectedDoctor->id }}">

        <div class="mb-3">
            <label class="form-label fw-semibold" for="availability_id">2. Choose an available slot</label>
            <select id="availability_id" name="availability_id" class="form-select" required>
                <option value="">Choose a date and time</option>
                @foreach ($selectedDoctor->availabilities as $slot)
                <option value="{{ $slot->id }}">
                    {{ $slot->available_date->format('M d, Y') }} at {{ substr($slot->available_time, 0, 5) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="reason">Reason <span class="text-muted">(optional)</span></label>
            <input id="reason" type="text" name="reason" class="form-control" value="{{ old('reason') }}">
        </div>

        <button class="btn btn-primary">Book appointment</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@else
<p class="text-muted"></p>
@endif
@else
<form method="POST" action="{{ $action }}">
    @csrf
    @method($method)

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="patient_id">Patient</label>
            <select id="patient_id" name="patient_id" class="form-select" required>
                @foreach ($patients as $patient)
                <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id ?? '') ==
                    $patient->id)>
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label" for="doctor_id">Doctor</label>
            <select id="doctor_id" name="doctor_id" class="form-select" required>
                @foreach ($doctors as $doctor)
                <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id ?? '') ==
                    $doctor->id)>
                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label" for="appointment_date">Date</label>
            <input id="appointment_date" type="date" name="appointment_date" class="form-control"
                value="{{ old('appointment_date', isset($appointment) && $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : '') }}"
                required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label" for="appointment_time">Time</label>
            <input id="appointment_time" type="time" name="appointment_time" class="form-control"
                value="{{ old('appointment_time', $appointment->appointment_time ?? '') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-select">
                @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                <option @selected(old('status', $appointment->status ?? 'pending') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label" for="reason">Reason <span class="text-muted">(optional)</span></label>
            <input id="reason" type="text" name="reason" class="form-control"
                value="{{ old('reason', $appointment->reason ?? '') }}">
        </div>
    </div>

    <button class="btn btn-primary">Save appointment</button>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endif