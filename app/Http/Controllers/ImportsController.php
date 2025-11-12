<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Import;
use App\Models\ImportLog;

class ImportsController extends Controller
{
    public function index() 
    {
        $types = [];

        if (auth()->user()->can('import-orders')) {
            $types[] = 'orders';
        }
        if (auth()->user()->can('import-products')) {
            $types[] = 'products';
        }
        if (auth()->user()->can('import-customers')) {
            $types[] = 'customers';
        }

        if (empty($types)) {
            abort(403); 
        } else {
            $imports = Import::with('user')
                            ->whereIn('type', $types)
                            ->latest()
                            ->paginate(10);
        }
        return view('imports-history.index', compact('imports'));
    }

    public function logs($id) 
    {
        $import = Import::with('user')->findOrFail($id);
        $logs = ImportLog::where('import_id', $id)
            ->orderBy('created_at')
            ->get();

        return view('imports-history.logs', compact('import', 'logs'));
    }
}
