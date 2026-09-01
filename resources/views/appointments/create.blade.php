@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div>

        <h1 class="mb-1">Create appointment</h1>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Back to appointments</a>
</div>

<div class="card appointment-form-card p-4 p-lg-5">
    @include('appointments.form', [
    'action' => route('appointments.store'),
    'method' => 'POST',
    ])
</div>
@endsection
