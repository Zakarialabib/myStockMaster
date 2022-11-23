<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    {
        $this->selected = $selected;
    }

    public function query()
    {
        if ($this->selected) {
            return Category::query()->whereIn('id', $this->selected);
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
            'Name',
            'Code',
            'Created At',
        ];
    }
}
