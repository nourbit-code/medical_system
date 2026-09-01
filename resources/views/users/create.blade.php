
@extends('layouts.app') @section('content')<p class="eyebrow">ADMINISTRATION</p>
<h1>Create account</h1>
<div class="card p-4 col-lg-7">
<form method="POST" action="{{ route('users.store') }}">
@csrf<label class="form-label">Name</label>
<input name="name" class="form-control mb-3" value="{{ old('name') }}" required>
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control mb-3" value="{{ old('email') }}" required>
<label class="form-label">Role</label>
<select name="role" class="form-select mb-3" required>
@foreach(['admin','doctor','patient'] as $role)<option value="{{ $role }}">{{ ucfirst($role) }}</option>
@endforeach</select>
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control mb-3" required>
<label class="form-label">Confirm password</label>
<input type="password" name="password_confirmation" class="form-control mb-3" required>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif<button class="btn btn-primary">Create account</button>
</form>
</div>
@endsection

