<?php

namespace App\Exports;

use App\Models\Customer;
use App\Exports\ForModelsTrait;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    protected $models;

    public function query()
    {
        if ($this->models) {

            return  Customer::query()->whereIn('id', $this->models);
        }

        return Customer::query();
    }

  
    public function map($row): array
    {
        return [
            $row->name,
            $row->email,
            $row->phone,
            $row->city,
            $row->country,
        ];
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Email'),
            __('Phone'),
            __('City'),
            __('Country'),
        ];
    }
}
