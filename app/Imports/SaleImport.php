<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;

class SaleImport implements ToModel
{
    /**  */
    public function __construct()
    {
    }

    /**
     * @param  array $row
     *
     * @return \App\Models\Sale
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Sale([
            'product_id'  => $row[0],
            'quantity'    => $row[1],
            'price'       => $row[2],
            'total'       => $row[3],
            'customer_id' => $row[4],
            'user_id'     => $row[5],
        ]);
    }
}
