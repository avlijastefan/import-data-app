<?php

namespace App\Http\Controllers;


class ImportFileController extends Controller
{
    public function show($type)
    {
        $config = config("imports.types.{$type}");

        if (!$config) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $files = collect($config['files'])->map(function ($file, $key) {
            return [
                'key' => $key,
                'label' => $file['label'],
            ];
        })->values();

        return response()->json([
            'files' => $files
        ]);
    }
}