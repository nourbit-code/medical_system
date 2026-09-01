@extends('layouts.app') @section('content')
    <div class="mb-4">

        <h1>My patients</h1>

        <form class="mb-4">
            <div class="input-group">
                <input name="search" class="form-control" placeholder="Search patient name" value="{{ request('search') }}">
                <button class="btn btn-outline-primary">Search</button>
            </div>
        </form>
        <div class="row g-3">
            @forelse($patients as $patient)
                <div class="col-md-6 col-xl-4">
                    <a href="{{ route('doctors.patients.show', $patient) }}" class="text-decoration-none text-dark">
                        <div class="card p-4 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar">{{ strtoupper(substr($patient->first_name, 0, 1)) }}</span>
                                <div>
                                    <h5 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                                    <small class="text-muted">{{ $patient->phone }}</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty<div class="col-12">
                    <div class="card p-4">No patients have appointments with you yet.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $patients->links() }}</div>
@endsection