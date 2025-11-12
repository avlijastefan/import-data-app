@extends('layouts.app')

@section('page_title', config("imports.types.{$type}.label"))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ config("imports.types.{$type}.label") }}</h3>
        <div>
            <form method="GET" class="form-inline d-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search..." value="{{ $search }}">
                <button type="submit" class="btn btn-info">Search</button>
            </form>
            <a href="{{ route('imported-data.export', $type) }}?search={{ $search }}" class="btn btn-success">Export</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    @foreach($headers as $col => $cfg)
                        <th>{{ $cfg['label'] }}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    @foreach(array_keys($headers) as $col)
                        <td>{{ $row->{$col} }}</td>
                    @endforeach
                    <td>
                        <button class="btn btn-sm btn-info" onclick="showAudit({{ $row->id }})">Audit</button>
                        @can(config("imports.types.{$type}.permission_required"))
                            <form method="POST" action="{{ route('imported-data.destroy', [$type, $row->id]) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->appends(request()->query())->links() }}
    </div>
</div>

<div class="modal fade" id="auditModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Audit History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="auditContent">Loading...</div>
        </div>
    </div>
</div>

<script>
function showAudit(id) {
    fetch(`/imported-data/{{ $type }}/${id}/audit`)
        .then(r => r.json())
        .then(data => {
            let html = '<table class="table table-sm"><tr><th>Column</th><th>Old</th><th>New</th></tr>';
            data.forEach(a => {
                html += `<tr><td>${a.column}</td><td>${a.old_value}</td><td>${a.new_value}</td></tr>`;
            });
            html += '</table>';
            document.getElementById('auditContent').innerHTML = html;
            $('#auditModal').modal('show');
        });
}
</script>
@endsection