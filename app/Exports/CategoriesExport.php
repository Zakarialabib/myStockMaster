<?php

namespace App\Exports;

use App\Models\Category;
use App\Exports\ForModelsTrait;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    protected $models;

    public function query()
    {
        if ($this->models) {

            return  Category::query()->whereIn('id', $this->models);
        }

        return Category::query();
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->code,
            $row->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Code'),
            __('Created At'),
        ];
    }
}
