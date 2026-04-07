<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoriesImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public function uniqueBy(): string
    {
        return 'code';
    }

    public function WithUpsertColumns(): array
    {
        return ['code', 'name'];
    }

    /**
     * @return Category
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
