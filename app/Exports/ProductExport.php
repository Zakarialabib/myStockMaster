<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    /** @return Builder|EloquentBuilder|Relation */
    public function query()
    {
        if ($this->models) {
            return Product::query()->whereIn('id', $this->models);
        }

        return Product::query();
    }

    /**
     * @param  Product  $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            $row->category?->name,
            $row->quantity,
            $row->cost,
            $row->price,
            $row->created_at->format('d-m-Y'),
        ];
    }

    public function headings(): array
    {
        return [
            __('Code'),
            __('Name'),
            __('Category'),
            __('Quantity'),
            __('Cost'),
            __('Price'),
            __('Created At'),
        ];
    }
}
