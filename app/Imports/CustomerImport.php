<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomerImport implements ToModel
{
    /**  */
    public function __construct()
    {
    }
    /**
     * @param  array $row
     *
     * @return \App\Models\Customer
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Customer([
            'name' => $row['name'],
            'phone' => $row['phone'],
        ]);
    }
}
