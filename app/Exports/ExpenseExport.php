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

    public function query()
    {
        if ($this->selected) {
            return Expense::query()->whereIn('id', $this->selected);
        }

        return Expense::query();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Amount',
            'Created At'
        ];
    }

    /**
     * @var Expense $row
     */
    public function map($row): array
    {
        return[
            $row->id,
            $row->name,
            $row->amount,
            $row->created_at,
        ];
    }
}
