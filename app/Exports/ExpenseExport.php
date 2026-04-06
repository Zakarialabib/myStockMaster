<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Expense::query()->whereIn('id', $this->models);
        }

        return Expense::query();
    }

    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->reference,
            $expense->amount,
            $expense->created_at->format('d-m-Y'),
        ];
    }

    public function headings(): array
    {
        return [
            __('#'),
            __('Reference'),
            __('Amount'),
            __('Created At'),
        ];
    }
}
