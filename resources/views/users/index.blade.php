@extends('layouts.app')

@section('page_title', 'Users')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center py-2 w-100">
        <h3 class="card-title mb-0">Users List</h3>
        @can('user-management')
            <a href="{{ route('users.create') }}" class="btn btn-primary ml-auto">
                Add User
            </a>
        @endcan
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        @if($user->permissions->count())
                            @foreach($user->permissions as $perm)
                                <span class="badge badge-info">{{ $perm->name }}</span>
                            @endforeach
                        @else
                            <em class="text-muted">No permissions</em>
                            @endif
                    </td>
                    <td>
                        @can('user-management')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this user?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>
@endsection