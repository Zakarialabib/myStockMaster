<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromQuery, WithMapping, WithHeadings
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
            return Product::query()->whereIn('id', $this->selected);
        }

        return Product::query();
    }

    /**
     * @var Product
     */
    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            $row->category->name,
            $row->quantity,
            $row->cost,
            $row->price,
            $row->created_at,
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
