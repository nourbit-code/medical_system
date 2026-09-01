@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Edit Patient</h1>

    <div class="card p-4">
        @include('patients.form', [
            'action' => route('patients.update', $patient),
            'method' => 'PUT',
        ])
    </div>
@endsection
