<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomerImport implements ToModel
{
    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Customer([
            'name' => $row['name'],
            'phone' => $row['phone'],
            'address' => $row['address'] ?? null,
            'tax_number' => $row['tax_number'] ?? null,
        ]);

    }
}
