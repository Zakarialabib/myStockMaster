<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierImport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    { 
        $this->selected = $selected;
    }
    /**
    * @var Supplier $supplier
    */
 
    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Created At'
        ];
    }

    public function map($supplier) : array
    {

        return[
        $supplier->id,
        $supplier->name,
        $supplier->email,
        $supplier->phone,
        $supplier->address,
        $supplier->created_at,
        ];

    }

    public function query()
    {
        return Supplier::query()->whereIn('id', $this->selected);
    }
}