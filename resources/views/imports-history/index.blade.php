@extends('layouts.app')

@section('page_title', 'Import History')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Import History</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($imports as $import)
                <tr>
                    <td>{{ $import->id }}</td>
                    <td>{{ $import->user->username }}</td>
                    <td>{{ config("imports.types.{$import->type}.label") ?? $import->type }}</td>
                    <td>{{ $import->file_name }}</td>
                    <td>
                        <span class="badge badge-{{ 
                                    $import->status === 'completed' ? 'success' :
                                    ($import->status === 'failed' ? 'danger' :
                                    ($import->status === 'processing' ? 'warning' : 'secondary'))
                                }} ">
                                    {{ ucfirst($import->status) }}
                        </span>
                    </td>
                    <td>{{ $import->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('imports-history.logs', $import->id) }}" class="btn btn-sm btn-info">Logs</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $imports->links() }}
    </div>
</div>
@endsection