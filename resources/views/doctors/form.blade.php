@if ($errors->any())
    <div class="alert alert-danger">
        Please fix the errors below.
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @method($method)

    <div class="row">
        @foreach (['first_name' => 'First Name', 'last_name' => 'Last Name', 'specialization' => 'Specialization', 'phone' => 'Phone', 'email' => 'Email'] as $field => $label)
            <div class="col-md-6 mb-3">
                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                <input
                    id="{{ $field }}"
                    type="{{ $field === 'email' ? 'email' : 'text' }}"
                    name="{{ $field }}"
                    class="form-control @error($field) is-invalid @enderror"
                    value="{{ old($field, $doctor->$field ?? '') }}"
                    required
                >
                @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
    </div>

    <button class="btn btn-primary">Save Doctor</button>
    <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Cancel</a>
</form>
