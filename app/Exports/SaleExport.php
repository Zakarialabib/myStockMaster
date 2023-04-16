<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Sale::query()->whereIn('id', $this->models);
        }

        return Sale::query();
    }

    /**
     * @param  Sale  $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->date,
            $row->reference,
            $row->customer->name,
            $row->total_amount,
            $row->due_amount,
        ];
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Reference'),
            __('Customer'),
            __('Total'),
            __('Due amount'),
        ];
    }
}
