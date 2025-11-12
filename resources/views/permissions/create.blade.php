@extends('layouts.app')

@section('page_title', 'Create Permission')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Permission</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf
            <div class="form-group">
                <label>Permission Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection