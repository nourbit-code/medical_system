@extends('layouts.app') @section('content')<div class="d-flex justify-content-between mb-3">
    <h1>Patients</h1>
    <a class="btn btn-primary" href="{{ route('patients.create') }}">+ Add Patient</a>
</div>
<form class="row g-2 mb-3">
    <div class="col-md-6">
        <input name="search" class="form-control" placeholder="Search name or phone" value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="sort" class="form-select">
            <option value="newest">Newest</option>
            <option value="oldest" @selected(request('sort')==='oldest' )>Oldest</option>
            <option value="name" @selected(request('sort')==='name' )>Name</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-primary">Search</button>
    </div>
</form>
<div class="card p-3 table-responsive">
    <table class="table align-middle">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Actions</th>
        </tr>
        @forelse($patients as $p)<tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->first_name }} {{ $p->last_name }}</td>
            <td>{{ $p->phone }}</td>
            <td>{{ ucfirst($p->gender) }}</td>
            <td>{{ $p->date_of_birth ?? '-' }}</td>
            <td>
                <a href="{{ route('patients.show',$p) }}" class="btn btn-sm btn-info">View</a> <a
                    href="{{ route('patients.edit',$p) }}" class="btn btn-sm btn-warning">Edit</a>
                <form class="d-inline" method="POST" action="{{ route('patients.destroy',$p) }}">
                    @csrf @method('DELETE')<button
                        onclick="return confirm('Are you sure you want to delete this record?')"
                        class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty<tr>
            <td colspan="6">No patients found.</td>
        </tr>
        @endforelse
    </table>{{ $patients->links() }}
</div>
@endsection