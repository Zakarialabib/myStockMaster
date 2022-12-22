<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use Helpers;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Product::create([
                'name'              => $row['name'],
                'description'       => $row['description'],
                'price'             => $row['price'],
                'old_price'         => $row['cost'] ?? null,
                'code'              => Str::random(10),
                'category_id'       => Category::where('name', $row['category'])->first()->id ?? Category::create(['name' => $row['category']])->id ?? null,
                'brand_id'          => Brand::where('name', $row['brand'])->first()->id ?? Brand::create(['name' => $row['brand']])->id ?? null,
                'image'             => Helpers::uploadImage($row['image']) ?? 'default.jpg', // upload fromm url
                'status'            => 0,
                'barcode_symbology' => 'c128',
                'quantity'          => 5, // change this
                'unit'              => 'pc', // change this
                'stock_alert'       => 0, // change this
                'order_tax'         => 0, // change this
                'tax_type'          => 'inclusive', // change this
            ]);
        }
    }
}
