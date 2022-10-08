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

    public function map($purchase): array
    {
        return [
            $purchase->product->name,
            $purchase->quantity,
            $purchase->price,
            $purchase->total,
            $purchase->supplier->name,
            $purchase->created_at,
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
