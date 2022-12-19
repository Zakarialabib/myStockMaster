<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;

class BrandsImport implements ToModel, SkipsEmptyRows
{
    /**
     * @param array $row
     * @return \App\Models\Brand
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Brand([
            'name'        => $row['name'],
            'image'       => $row['image'] ?? null, // or download with url
            'description' => $row['description'] ?? null,
        ]);
    }
}
