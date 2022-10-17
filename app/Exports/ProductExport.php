<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    { 
        $this->selected = $selected;
    }
    /**
    * @var Product $product
    */

    public function query()
    {
        if($this->selected){
            return Product::query()->whereIn('id', $this->selected);
        } else {
            return Product::query();
        }
    }

    public function map($product): array
    {
        return [
            $product->code,
            $product->name,
            $product->category->name,
            $product->quantity,
            $product->cost,
            $product->price,
            $product->created_at,
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