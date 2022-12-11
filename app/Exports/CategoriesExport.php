<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    /** @return Builder|EloquentBuilder|Relation */
    public function query()
    {
        if ($this->models) {
            return  Category::query()->whereIn('id', $this->models);
        }

        return Category::query();
    }

    /**
     * @param mixed $row
     * @return array
     */
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
