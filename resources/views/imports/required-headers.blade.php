<div class="alert alert-info mt-3">
    <strong>Required Headers:</strong>
    @foreach($config['files'] as $key => $file)
        <div class="mt-2">
            <!--<strong>{{ $file['label'] }} ({{ $key }}.csv/.xlsx):</strong><br> -->
            @php
                $headers = $file['required_headers'];
                $formatted = collect($headers)
                            ->map(fn($h) => "<code style='color: white;'>{$h}</code>")
                            ->implode(', ');
            @endphp
            {!! $formatted !!}
        </div>
    @endforeach
</div>