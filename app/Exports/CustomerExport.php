<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, WithMapping, WithHeadings
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
            return Customer::query()->whereIn('id', $this->selected);
        }

        return Customer::query();
    }

    /**
     * @var Customer
     */
    public function map($row): array
    {
        return [
            $row->id,
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
            '#',
            __('Name'),
            __('Email'),
            __('Phone'),
            __('City'),
            __('Country'),
        ];
    }
}
