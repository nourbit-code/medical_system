@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Edit Doctor</h1>

    <div class="card p-4">
        @include('doctors.form', [
            'action' => route('doctors.update', $doctor),
            'method' => 'PUT',
        ])
    </div>
@endsection
