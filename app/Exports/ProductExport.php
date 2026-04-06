<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
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

    public function query()
    {
        if ($this->models) {
            return Product::query()->with('category')->whereIn('id', $this->models);
        }

        return Product::query()->with('category');
    }

    public function map($product): array
    {
        return [
            $product->code,
            $product->name,
            $product->category?->name,
            $product->quantity,
            $product->cost,
            $product->price,
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
        ];
    }
}
