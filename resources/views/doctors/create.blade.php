@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Add Doctor</h1>

    <div class="card p-4">
        @include('doctors.form', [
            'action' => route('doctors.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
