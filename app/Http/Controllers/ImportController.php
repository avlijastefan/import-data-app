<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Import;
use App\Jobs\ProcessImport;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImportStoreRequest;

class ImportController extends Controller
{
    public function index() 
    {
        $availableTypes = [];
        foreach (config('imports.types') as $type => $conf) {
            if (auth()->user()->can($conf['permission_required'])) {
                $availableTypes[$type] = $type;
            }
        }

        if (empty($availableTypes)) {
            return redirect('/dashboard'); // Hide the tab if the user has no permission
        }

        return view('imports.index', compact('availableTypes'));
    }

    public function store(ImportStoreRequest $request)
    {
        $type = $request->type;
        $config = config("imports.types.{$type}");

        // Permission check
        if (!auth()->user()->can($config['permission_required'] ?? '')) {
            return back()->withErrors(['permission' => 'You do not have permission.']);
        }

        $fileKey = 'main';

        // Validate file presence
        if (!$request->hasFile($fileKey)) {
            return back()->withErrors(['main' => 'File is required.']);
        }

        $file = $request->file($fileKey);

        // Validate file extension
        if (!in_array($file->extension(), ['csv', 'xlsx'])) {
            return back()->withErrors(['main' => 'Only .csv and .xlsx files are allowed.']);
        }

        // 1. Store the file
        $path = $file->store('imports'); // Returns: imports/filename.csv
        $fullPath = storage_path('app/' . $path); // Full path: C:\...\storage\app\imports\...

        // 2. Verify file exists
        if (!file_exists($fullPath)) {
            return back()->withErrors(['main' => 'File was not saved. Check storage directory.']);
        }

        // 3. Read and validate file
        try {
            $import = new class implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                public function collection(\Illuminate\Support\Collection $rows)
                {
                    return $rows;
                }
            };

            $collection = Excel::toCollection($import, $fullPath)->first();

            if ($collection->isEmpty()) {
                return back()->withErrors(['main' => 'The file is empty.']);
            }

            // Trim whitespace from headers
            $headers = $collection->first()->keys()->map('trim')->toArray();
            $required = $config['files']['main']['required_headers'];
            $missing = array_diff($required, $headers);

            if (!empty($missing)) {
                return back()->withErrors([
                    'main' => 'Missing required columns: <strong>' . implode(', ', $missing) . '</strong>'
                ]);
            }

        } catch (\Exception $e) {
            return back()->withErrors([
                'main' => 'Error reading file: ' . $e->getMessage()
            ]);
        }

        // 4. Create import record
        $importRecord = \App\Models\Import::create([
            'user_id'     => auth()->id(),
            'type'        => $type,
            'file_name'   => $file->getClientOriginalName(),
            'status'      => 'processing',
            'started_at'  => now(),
        ]);

        // 5. Dispatch job
        \App\Jobs\ProcessImport::dispatch($importRecord, [$fileKey => $fullPath], $type);

        return back()->with('success', 'Import started successfully! Check history for progress.');
    }

    public function headers($type)
    {
        $config = config("imports.types.{$type}");

        if (!$config || !auth()->user()->can($config['permission_required'] ?? '')) {
            abort(404);
        }

        return view('imports.required-headers', compact('config'));
    }
}
