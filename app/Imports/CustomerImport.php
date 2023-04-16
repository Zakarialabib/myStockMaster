<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow, SkipsEmptyRows
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
            'name'  => $row['name'],
            'phone' => $row['phone'],
        ]);
    }
}
