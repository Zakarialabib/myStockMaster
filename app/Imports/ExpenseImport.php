<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExpenseImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row): Expense
    {
        return new Expense([
            'category_id'  => ExpenseCategory::where('name', $row['category'])->first()->id ?? ExpenseCategory::create(['name' => $row['category']])->id ?? null,
            'user_id'      => auth()->user()->id,
            'warehouse_id' => Warehouse::where('name', $row['warehouse'])->first()->id ?? Warehouse::create(['name' => $row['warehouse']])->id ?? null,
            'date'         => $row['date'] ?? date('Y-m-d'),
            // 'reference'    => settings()->expense_prefix,
            'description' => $row['description'] ?? null,
            'amount'      => $row['amount'],
        ]);
    }
}
