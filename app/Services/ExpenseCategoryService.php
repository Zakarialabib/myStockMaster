<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryService
{
    public function create(array $data): ExpenseCategory
    {
        return DB::transaction(function () use ($data) {
            return ExpenseCategory::create($data);
        });
    }

    public function update(ExpenseCategory $category, array $data): ExpenseCategory
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update($data);

            return $category;
        });
    }
}
