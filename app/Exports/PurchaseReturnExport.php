<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\PurchaseReturn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseReturnExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return PurchaseReturn::query()->whereIn('id', $this->models);
        }

        return PurchaseReturn::query();
    }

    /**
     * @param PurchaseReturn $row
     */
    public function map($row): array
    {
        return [
            $row->date,
            $row->reference,
            $row->supplier->name,
            $row->total_amount,
            $row->due_amount,
        ];
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Reference'),
            __('Supplier'),
            __('Total'),
            __('Due amount'),
        ];
    }
}
