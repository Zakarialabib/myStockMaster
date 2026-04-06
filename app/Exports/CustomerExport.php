<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function __construct() {}

    public function query()
    {
        if ($this->models) {
            return Customer::query()->whereIn('id', $this->models);
        }

        return Customer::query();
    }

    public function map($customer): array
    {
        return [
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
            __('Name'),
            __('Email'),
            __('Phone'),
            __('City'),
            __('Country'),
        ];
    }
}
