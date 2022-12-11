<?php

namespace App\Exports;

use App\Models\Supplier;
use App\Exports\ForModelsTrait;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    protected $models;

    public function query()
    {
        if ($this->models) {
            return Supplier::query()->whereIn('id', $this->models);
        }

        return Supplier::query();
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Email'),
            __('Phone'),
            __('City'),
            __('Country'),
            __('Address'),
            __('Tax number'),
        ];
    }

    public function map($row): array
    {
        return[
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
