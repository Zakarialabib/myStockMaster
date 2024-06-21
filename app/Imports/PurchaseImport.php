<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\ToModel;

class PurchaseImport implements ToModel
{
    /**  */
    public function __construct()
    {
    }

    /**
     * @param  array $row
     *
     * @return Purchase
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Purchase([
            'product_id'  => $row[0],
            'quantity'    => $row[1],
            'price'       => $row[2],
            'total'       => $row[3],
            'date'        => $row[4],
            'supplier_id' => $row[5],
            'user_id'     => $row[6],
        ]);
    }
}
