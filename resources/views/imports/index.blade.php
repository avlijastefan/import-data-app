@extends('layouts.app')

@section('page_title', 'Data Import')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Import Data</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('imports.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Import Type</label>
                <select name="type" class="form-control" id="import-type" required>
                    <option value="">-- Select --</option>
                    @foreach($availableTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div id="file-inputs"></div>
            <div id="required-headers"></div>

            <button type="submit" id="submit-btn" class="btn btn-primary" disabled>Start Import</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('import-type');
        const fileContainer = document.getElementById('file-inputs');
        const headersDiv = document.getElementById('required-headers');
        const submitBtn = document.getElementById('submit-btn');

        typeSelect.addEventListener('change', function () {
            const type = this.value;

            // Reset
            fileContainer.innerHTML = '';
            headersDiv.innerHTML = '';
            submitBtn.disabled = true;

            if (!type) return;

            fetch(`/imports/headers/${type}`)
                .then(r => r.ok ? r.text() : Promise.reject())
                .then(html => headersDiv.innerHTML = html)
                .catch(() => headersDiv.innerHTML = '<div class="alert alert-danger">Error loading header.</div>');

            fetch(`/import-file/${type}`)
                .then(r => r.ok ? r.json() : Promise.reject())
                .then(config => {
                    fileContainer.innerHTML = '';

                    const file = config.files[0]; // main
                    const div = document.createElement('div');
                    div.className = 'form-group mb-3';

                    div.innerHTML = `
                        <label class="form-label">
                            ${file.label} <small class="text-muted">(${file.key}.csv/.xlsx)</small>
                        </label>
                        <input type="file" name="${file.key}" class="form-control file-input" accept=".csv,.xlsx" required>
                    `;

                    fileContainer.appendChild(div);

                    const fileInput = fileContainer.querySelector('.file-input');

                    const enableButton = () => {
                        const hasFile = fileInput && fileInput.files && fileInput.files[0];
                        submitBtn.disabled = !hasFile;
                        console.log('File selected:', hasFile); 
                    };

                    fileInput.addEventListener('change', enableButton);
                    enableButton();
                })
                .catch(err => {
                    console.error('Config error:', err);
                    fileContainer.innerHTML = '<div class="alert alert-danger">Error loading configuration.</div>';
                    submitBtn.disabled = true;
                });
        });
    });
</script>
@endsection