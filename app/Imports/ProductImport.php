<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class ProductImport implements ToModel, SkipsEmptyRows
{
    /**
     * @param  array $row
     * @return \App\Models\Product
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
    }
}
