<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuotationExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Quotation::query()->whereIn('id', $this->models);
        }

        return Quotation::query();
    }

    /**
     * @param Quotation $row
     */
    public function map($row): array
    {
        return [
            $row->date,
            $row->reference,
            $row->customer->name,
            $row->total_amount,
            $row->status,
        ];
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Reference'),
            __('Customer'),
            __('Total'),
            __('Status'),
        ];
    }
}
