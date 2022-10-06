<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    { 
        $this->selected = $selected;
    }
    /**
    * @var Expense $expense
    */
 
    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Amount',
            'Created At'
        ];
    }

    public function map($expense) : array
    {

        return[
        $expense->id,
        $expense->name,
        $expense->amount,
        $expense->created_at,
        ];

    }

    public function query()
    {
        return Expense::query()->whereIn('id', $this->selected);
    }
}