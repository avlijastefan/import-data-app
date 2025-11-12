<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use App\Models\AuditLog;

class ImportedDataController extends Controller
{
    public function show($type) 
    {
       $config = config("imports.types.{$type}");
        if (!$config || !isset($config['files']['main'])) {
            abort(404);
        }

        $mainConfig = $config['files']['main'];
        $model = $mainConfig['model'];
        $headers = [];
        foreach ($mainConfig['headers_to_db'] as $dbColumn => $cfg) {
            $headers[$dbColumn] = [
                'label' => $cfg['label'],
                'type'  => $cfg['type'] ?? 'string',
            ];
        }
        $search = request('search');
        $query = $model::query();

        if ($search) {
            foreach ($config['files']['main']['headers_to_db'] as $col) {
                $query->orWhere($col['db_column'], 'like', '%' . $request->search . '%');
            }
        }

        $data = $query->paginate(10);

        return view('imported-data.show', compact('type', 'data', 'headers', 'search', 'config'));
    }

    public function export($type, Request $request) 
    {
        return Excel::download(new GenericExport($type, $request->search), "{$type}.xlsx");
    }

    public function destroy($type, $id) 
    {
        $config = config("imports.types.{$type}.files.main");
        if (auth()->user()->can($config['permission_required'] ?? '')) {
            abort(403);
        }

        $model = $config['model'];
        $model::findOrFail($id)->delete();

        return back()->with('success', 'Record deleted successfully.');
    }

    public function audit($type, $id) 
    {
        $audits = AuditLog::where('import_id', $id)
        ->select('column', 'old_value', 'new_value')
        ->get();

        return response()->json($audits);
    }
}
