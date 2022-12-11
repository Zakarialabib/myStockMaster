<?php

namespace App\Exports;

use App\Models\Expense;
use App\Exports\ForModelsTrait;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    protected $models;

    public function query()
    {
        if ($this->models) {

            return  Expense::query()->whereIn('id', $this->models);
        }

        return Expense::query();
    }

    public function headings(): array
    {
        return [
            '#',
            'Reference',
            'Amount',
            'Created At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->reference,
            $row->amount,
            $row->created_at->format('d-m-Y'),
        ];
    }
}
