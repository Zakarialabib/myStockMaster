<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CategoriesImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     * @return \App\Models\Category
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Category([
            'code' => $row['code'] ?? Str::random(5),
            'name' => $row['name'],
        ]);
    }

    /**
     */
    public function __construct() {
        //
    }
}
