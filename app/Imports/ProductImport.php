<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{

     /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name' => $row[0],
            'description' => $row[1],
            'price' => $row[2],
            'quantity' => $row[3],
            'category_id' => $row[4],
            'brand_id' => $row[5],
            'image' => $row[6],
        ]);
    }
    
}