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
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->product->name,
            $row->quantity,
            $row->price,
            $row->total,
            $row->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Product',
            'Quantity',
            'Price',
            'Total',
            'Date',
        ];
    }
}
