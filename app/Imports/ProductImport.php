<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class ProductImport implements ToModel, SkipsEmptyRows
{
    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'name'              => $row[0],
            'code'              => $row[0] ?? Str::random(5),
            'description'       => $row[1] ?? null,
            'cost'              => $row[1],
            'price'             => $row[2],
            'quantity'          => $row[3],
            'category_id'       => $row[4],
            'brand_id'          => $row[5],
            'image'             => $row[6] ?? null,
            'created_at'        => Carbon::parse($row[7]),
            'barcode_symbology' => 'c128',
            'unit'              => 'pc',
            'stock_alert'       => '10',
        ]);
use App\Models\Subcategory;
use Helpers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Str;

class ProductImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Product::create([
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'old_price' => $row['cost'] ?? null,
                'code' => Str::random(10),
                'category_id' => Category::where('name', $row['category'])->first()->id ?? Category::create(['name' => $row['category']])->id ?? null,
                'brand_id' => Brand::where('name', $row['brand'])->first()->id ?? Brand::create(['name' => $row['brand']])->id ?? null,
                'image' => Helpers::uploadImage($row['image']) ?? 'default.jpg', // upload fromm url 
                'status' => 0,
                'barcode_symbology' => 'c128',
                'quantity' = 5,
                'unit' => 'pc',
                'stock_alert'=> 0,
                'order_tax' => 0,
                'tax_type' => 'inclusive'
            ]);
        }
    }
}
