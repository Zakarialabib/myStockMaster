<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
class CategoriesImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**  */
    public function __construct()
    {
    }
    public function uniqueBy()
    {
        return 'code';
    }
    public function WithUpsertColumns()
    {
        return ['code','name'];
    }
    /**
     * @param array $row
     *
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
}
