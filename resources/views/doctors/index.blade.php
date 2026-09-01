
@extends('layouts.app') @section('content')<div class="d-flex justify-content-between mb-3">
<h1>Doctors</h1>
<a class="btn btn-primary" href="{{ route('doctors.create') }}">+ Add Doctor</a>
</div>
<form class="row g-2 mb-3">
<div class="col-md-6">
<input name="search" class="form-control" placeholder="Search name or specialization" value="{{ request('search') }}">
</div>
<div class="col-md-3">
<select name="sort" class="form-select">
<option value="newest">Newest</option>
<option value="oldest">Oldest</option>
<option value="name">Name</option>
</select>
</div>
<div class="col-md-2">
<button class="btn btn-outline-primary">Search</button>
</div>
</form>
<div class="card p-3 table-responsive">
<table class="table">
<tr>
<th>ID</th>
<th>Name</th>
<th>Specialization</th>
<th>Phone</th>
<th>Email</th>
<th>Actions</th>
</tr>
@forelse($doctors as $d)<tr>
<td>{{ $d->id }}</td>
<td>Dr. {{ $d->first_name }} {{ $d->last_name }}</td>
<td>{{ $d->specialization }}</td>
<td>{{ $d->phone }}</td>
<td>{{ $d->email ?: '-' }}</td>
<td>
<a href="{{ route('doctors.show',$d) }}" class="btn btn-sm btn-info">View</a> <a href="{{ route('doctors.edit',$d) }}" class="btn btn-sm btn-warning">Edit</a>
<form class="d-inline" method="POST" action="{{ route('doctors.destroy',$d) }}">
@csrf @method('DELETE')<button onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-sm btn-danger">Delete</button>
</form>
</td>
</tr>
@empty<tr>
<td colspan="6">No doctors found.</td>
</tr>
@endforelse</table>{{ $doctors->links() }}</div>
@endsection

