@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Add Patient</h1>

    <div class="card p-4">
        @include('patients.form', [
            'action' => route('patients.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
