<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Helpers;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            Product::query()->create([
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'old_price' => $row['cost'] ?? null,
                'code' => $row['code'] ?? Str::random(10),
                'category_id' => Category::query()->where('name', $row['category'])->first()->id ?? Category::query()->create(['name' => $row['category']])->id ?? null,
                'brand_id' => Brand::query()->where('name', $row['brand'])->first()->id ?? Brand::query()->create(['name' => $row['brand']])->id ?? null,
                // 'image' => Helpers::uploadImage($row['image']) ?? 'default.jpg', // upload fromm url
                'status' => 0,
                'barcode_symbology' => 'c128',
                'quantity' => $row['quantity'] ?? 1,
                'unit' => 'pc', // change this
                'stock_alert' => $row['stock_alert'] ?? 10,
                'tax_amount' => 0, // change this
                'tax_type' => 'inclusive', // change this
            ]);
        }
    }
}
