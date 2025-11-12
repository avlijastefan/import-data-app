@extends('layouts.app')

@section('page_title', 'Permissions')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center py-2 w-100">
        <h3 class="card-title">Permissions List</h3>
        @can('user-management')
            <a href="{{ route('permissions.create') }}" class="btn btn-primary ml-auto">Add Permission</a>
        @endcan
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $perm)
                <tr>
                    <td>{{ $perm->id }}</td>
                    <td>{{ $perm->name }}</td>
                    <td>
                        @can('user-management')
                            <a href="{{ route('permissions.edit', $perm) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('permissions.destroy', $perm) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this permission?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $permissions->links() }}
    </div>
</div>
@endsection