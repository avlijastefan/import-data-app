<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GenericExport implements FromCollection, WithHeadings, WithMapping
{

    protected $type;
    protected $search;

    public function __construct($type, $search = null) 
    {
        $this->type = $type;
        $this->search = $search;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $config = config('imports.types.' . $this->type);
        $model = $config['files']['main']['model'];
        $query = $model::query();

        if ($this->search) {
            foreach ($config['headers_to_db'] as $header => $col) {
                $query->orWhere($col['db_column'], 'like', "%{$this->search}%");
            }
        }
        return $query->get();
    }

    public function headings(): array 
    {
        $config = config('imports.types.' . $this->type);
        return array_keys($config['files']['main']['headers_to_db']);
    }

    public function map($row): array
    {
        $mapped = [];
        $headers = config("imports.types.{$this->type}.files.main.headers_to_db");
        foreach ($headers as $header => $col) {
            $mapped[] = $row->{$header};
        }
        return $mapped;
    }
}
