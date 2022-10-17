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
    /**
    * @var Supplier $supplier
    */

    public function query()
    {
        if($this->selected){
            return Supplier::query()->whereIn('id', $this->selected);
        } else {
            return Supplier::query();
        }
        
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
  

    public function map($supplier) : array
    {

        return[
        $supplier->id,
        $supplier->name,
        $supplier->email,
        $supplier->phone,
        $supplier->city,
        $supplier->country,
        $supplier->address,
        $supplier->tax_number,
        ];

    }

  
}