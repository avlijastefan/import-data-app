@extends('layouts.app')

@section('page_title', 'Import Logs #'.$import->id)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Logs for Import #{{ $import->id }}</h3>
    </div>
    <div class="card-body">
        @if($logs->count() == 0)
            <p class="text-success">No errors found.</p>
        @else
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Row</th>
                        <th>Column</th>
                        <th>Value</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->row_number }}</td>
                        <td>{{ $log->column }}</td>
                        <td><code>{{ Str::limit($log->value, 50) }}</code></td>
                        <td>{{ $log->message }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <a href="{{ route('imports-history.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection