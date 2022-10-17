<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BrandsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Brand([
            'name' => $row['name'],
            'image' => $row['image'],
        ]);
    }
}