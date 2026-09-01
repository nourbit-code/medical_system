@if ($errors->any())
    <div class="alert alert-danger">
        Please fix the errors below.
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @method($method)

    <div class="row">
        @foreach ([
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'date_of_birth' => 'Date of Birth',
            'emergency_contact' => 'Emergency Contact',
        ] as $field => $label)
            <div class="col-md-6 mb-3">
                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                <input
                    id="{{ $field }}"
                    type="{{ $field === 'email' ? 'email' : ($field === 'date_of_birth' ? 'date' : 'text') }}"
                    name="{{ $field }}"
                    class="form-control @error($field) is-invalid @enderror"
                    value="{{ old($field, $patient->$field ?? '') }}"
                    @if (in_array($field, ['first_name', 'last_name', 'phone'])) required @endif
                >
                @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div class="col-md-6 mb-3">
            <label class="form-label" for="gender">Gender</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="">Select</option>
                @foreach (['male', 'female', 'other'] as $gender)
                    <option value="{{ $gender }}" @selected(old('gender', $patient->gender ?? '') === $gender)>
                        {{ ucfirst($gender) }}
                    </option>
                @endforeach
            </select>
            @error('gender')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 mb-3">
            <label class="form-label" for="address">Address</label>
            <textarea id="address" name="address" class="form-control">{{ old('address', $patient->address ?? '') }}</textarea>
        </div>
    </div>

    <button class="btn btn-primary">Save Patient</button>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancel</a>
</form>
