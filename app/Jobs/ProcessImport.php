<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\Import;
use App\Models\ImportLog;
use App\Models\AuditLog;
use App\Events\ImportFailed;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $import;
    protected $files;
    protected $type;

    public function __construct(Import $import, array $files, string $type)
    {
        $this->import = $import;
        $this->files = $files;
        $this->type = $type;
    }

    public function handle(): void
    {
        // Start of import
        $this->import->update([
            'status' => 'processing',
            'started_at' => now()
        ]);

        $config = config('imports.types.' . $this->type);
        $hasError = false;

        foreach ($config['files'] as $fileKey => $fileConfig) {
            if (!isset($this->files[$fileKey])) {
                continue;
            }

            $collection = Excel::toCollection([], $this->files[$fileKey])->first();
            if ($collection->isEmpty()) {
                $hasError = true;
                continue;
            }

            $headers = $collection->shift()->toArray();

            foreach ($collection as $rowNumber => $row) {
                $data = [];
                foreach ($fileConfig['headers_to_db'] as $header => $colConfig) {
                    $index = array_search($header, $headers);
                    $value = $row[$index] ?? null;

                    switch ($colConfig['type'] ?? 'string') {
                        case 'integer':
                            $value = $value !== null ? (int)$value : null;
                            break;
                        case 'double':
                            $value = $value !== null ? (float)str_replace(',', '.', $value) : null;
                            break;
                        case 'date':
                            $value = $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
                            break;
                        case 'string':
                        default:
                            $value = $value !== null ? trim((string)$value) : null;
                            break;
                    }

                    $data[$header] = $value;
                }

                // Validation
                $validator = Validator::make($data, $fileConfig['validation'] ?? []);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        ImportLog::create([
                            'import_id'  => $this->import->id,
                            'row_number' => $rowNumber + 2,
                            'column'     => 'validation',
                            'value'      => json_encode($data),
                            'message'    => $message,
                        ]);
                    }
                    $hasError = true;
                    continue;
                }

                // Unique keys
                $unique = array_intersect_key($data, array_flip($fileConfig['update_or_create']));
                $modelClass = $fileConfig['model'];

                $existing = $modelClass::where($unique)->first();

                // Save audit if it exists and if it changes
                if ($existing) {
                    foreach ($data as $column => $newValue) {
                        $oldValue = $existing->{$column};
                        if ($oldValue != $newValue) {
                            AuditLog::create([
                                'import_id'  => $this->import->id,
                                'record_id'  => $existing->id,
                                'column'     => $column,
                                'old_value'  => is_scalar($oldValue) ? (string)$oldValue : json_encode($oldValue),
                                'new_value'  => is_scalar($newValue) ? (string)$newValue : json_encode($newValue),
                            ]);
                        }
                    }
                }

                // Update or create
                $record = $modelClass::updateOrCreate($unique, $data);
            }
        }

        // End of import
        if ($hasError) {
            $this->import->update([
                'status' => 'failed',
                'finished_at' => now()
            ]);
            event(new ImportFailed($this->import));
        } else {
            $this->import->update([
                'status' => 'completed',
                'finished_at' => now()
            ]);
        }
    }
}