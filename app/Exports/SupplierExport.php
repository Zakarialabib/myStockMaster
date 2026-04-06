<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    public function query()
    {
        if ($this->models) {
            return Supplier::query()->whereIn('id', $this->models);
        }

        return Supplier::query();
    }

    public function map($supplier): array
    {
        return [
            $supplier->name,
            $supplier->email,
            $supplier->phone,
            $supplier->city,
            $supplier->country,
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
