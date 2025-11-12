@extends('layouts.app')

@section('page_title', 'Create User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New User</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" required>
                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Permissions</label>
                <div class="row">
                    @foreach($permissions as $perm)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                   class="form-check-input" id="perm-{{ $perm->id }}">
                            <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $perm->name }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection