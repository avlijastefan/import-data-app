@extends('layouts.app')

@section('page_title', 'Edit Permission: ' . $permission->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Permission</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Permission Name</label>
                <input type="text" name="name" value="{{ old('name', $permission->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection