@extends('layouts.app') @section('content')<div class="d-flex justify-content-between align-items-center mb-4">
    <div>

        <h1>System accounts</h1>

    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Create account</a>
</div>
<div class="card p-3 table-responsive">
    <table class="table align-middle">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        @foreach($users as $user)<tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <span class="badge bg-primary-subtle text-primary">{{ ucfirst($user->role) }}</span>
            </td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('users.edit',$user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form class="d-inline" method="POST" action="{{ route('users.destroy',$user) }}">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Delete this account?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>{{ $users->links() }}
</div>
@endsection
