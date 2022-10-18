<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;

class SupplierImport implements ToModel
{
     /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $supplier = new Supplier([
           'name' => $row[0],
           'email' => $row[1],
           'phone' => $row[2],
           'address' => $row[3],
        ]);
    }
}