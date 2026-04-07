<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryService
{
    public function create(array $data): ExpenseCategory
    {
        return DB::transaction(fn() => ExpenseCategory::query()->create($data));
    }

    public function update(ExpenseCategory $expenseCategory, array $data): ExpenseCategory
    {
        return DB::transaction(function () use ($expenseCategory, $data): \App\Models\ExpenseCategory {
            $expenseCategory->update($data);

            return $expenseCategory;
        });
    }
}
