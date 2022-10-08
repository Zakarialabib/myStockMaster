<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductImport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Product::query();
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->description,
            $product->price,
            $product->quantity,
            $product->category->name,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Price',
            'Quantity',
            'Category',
        ];
    }
}