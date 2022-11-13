<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierExport implements FromQuery, WithMapping, WithHeadings
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
            return Supplier::query()->whereIn('id', $this->selected);
        }
        return Supplier::query();
    }

    public function headings(): array
    {
        return [
            '#',
            __('Name'),
            __('Email'),
            __('Phone'),
            __('City'),
            __('Country'),
            __('Address'),
            __('Tax number'),
        ];
    }

    /**
     * @var Supplier $row
     */
    public function map($row): array
    {
        return[
        $row->id,
        $row->name,
        $row->email,
        $row->phone,
        $row->city,
        $row->country,
        $row->address,
        $row->tax_number,
        ];
    }
}
