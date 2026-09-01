@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (request()->is('register'))
<div class="mb-3">
    <label class="form-label" for="name">Full name</label>
    <input id="name" name="name" class="form-control" value="{{ old('name') }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="role">Account type</label>
    <select id="role" name="role" class="form-select" required>
        <option value="">Select account type</option>
        <option value="doctor" @selected(old('role')==='doctor' )>Doctor</option>
        <option value="patient" @selected(old('role')==='patient' )>Patient</option>
    </select>
</div>
@endif

<div class="mb-3">
    <label class="form-label" for="email">Email address</label>
    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="password">Password</label>
    <input id="password" type="password" name="password" class="form-control" required>
</div>

@if (request()->is('register'))
<div class="mb-3">
    <label class="form-label" for="password_confirmation">Confirm password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
</div>
@endif
