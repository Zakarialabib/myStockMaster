<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Purchase::query();
    }

    /**
     * @param Purchase $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->product->name,
            $row->quantity,
            $row->price,
            $row->total,
            $row->supplier->name,
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
            'Supplier',
            'Created At',
        ];
    }
}
