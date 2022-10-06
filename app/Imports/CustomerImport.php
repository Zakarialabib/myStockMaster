<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;

class CustomerImport implements ToModel
{
     /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $customer = new Customer([
           'name' => $row[0],
           'email' => $row[1],
           'phone' => $row[2],
           'address' => $row[3],
        ]);

        Auth::user()->customers()->save($customer);
    }
}