@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <div class="eyebrow">ACCOUNT SETTINGS</div>

    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix these items:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('account.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card p-4">
                    <h2 class="h4 mb-4">Personal information</h2>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First name</label>
                            <input id="first_name" type="text" name="first_name" class="form-control"
                                value="{{ old('first_name', optional($user->doctor ?? $user->patient)->first_name ?? '') }}"
                                required>
                            @error('first_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last name</label>
                            <input id="last_name" type="text" name="last_name" class="form-control"
                                value="{{ old('last_name', optional($user->doctor ?? $user->patient)->last_name ?? '') }}"
                                required>
                            @error('last_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-7">
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        @if ($user->role !== 'admin')
                            <div class="col-md-5">
                                <label for="phone" class="form-label">Phone</label>
                                <input id="phone" type="text" name="phone" class="form-control"
                                    value="{{ old('phone', optional($user->doctor ?? $user->patient)->phone ?? '') }}" required>
                                @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        @endif
                    </div>

                    @if ($user->role === 'doctor')
                        <div class="row g-3 mt-1">
                            <div class="col-md-7">
                                <label for="specialization" class="form-label">Medical specialization</label>
                                <input id="specialization" type="text" name="specialization" class="form-control"
                                    value="{{ old('specialization', $user->doctor->specialization) }}" required>
                                @error('specialization')
                                <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label for="date_of_birth" class="form-label">Date of birth</label>
                                <input id="date_of_birth" type="date" name="date_of_birth" class="form-control"
                                    value="{{ old('date_of_birth', optional($user->doctor->date_of_birth)->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    @elseif ($user->role === 'patient')
                        <div class="row g-3 mt-1">
                            <div class="col-md-5">
                                <label for="date_of_birth" class="form-label">Date of birth</label>
                                <input id="date_of_birth" type="date" name="date_of_birth" class="form-control"
                                    value="{{ old('date_of_birth', optional($user->patient->date_of_birth)->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" class="form-select" required>
                                    @foreach (['male', 'female', 'other'] as $gender)
                                        <option value="{{ $gender }}" @selected(
                                            old('gender', $user->patient->gender) ===
                                            $gender
                                        )>{{ ucfirst($gender) }}</option>
                                    @endforeach
                                </select>
                                @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control"
                                rows="2">{{ old('address', $user->patient->address) }}</textarea>
                            @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mt-3">
                            <label for="emergency_contact" class="form-label">Emergency contact</label>
                            <input id="emergency_contact" type="text" name="emergency_contact" class="form-control"
                                value="{{ old('emergency_contact', $user->patient->emergency_contact) }}">
                            @error('emergency_contact')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card p-4 mb-4">
                    <h2 class="h4 mb-3">Account details</h2>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar" style="width:52px;height:52px;font-size:1.2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ ucfirst($user->role) }} account</div>
                            <div class="text-muted small">Member since {{ $user->created_at->format('M Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <h2 class="h4 mb-3">Change password</h2>
                    <label for="password" class="form-label">New password</label>
                    <input id="password" type="password" name="password" class="form-control mb-3">
                    <label for="password_confirmation" class="form-label">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                    @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">Save changes</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
@endsection