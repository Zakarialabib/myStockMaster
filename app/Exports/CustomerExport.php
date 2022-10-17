<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    { 
        $this->selected = $selected;
    }

    /**
    * @var Customer $customer
    */
    public function query()
    {
        if($this->selected){
            return Customer::query()->whereIn('id', $this->selected);
        } else {
            return Customer::query();
        }
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->city,
            $customer->country,
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