<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SaleReturn;
use Illuminate\Http\Response;

class ExportSaleReturnController extends Controller
{
    public function __invoke(int|string $id): Response
    {
        $saleReturn = SaleReturn::query()->where('id', $id)->firstOrFail();
        $customer = Customer::query()->where('id', $saleReturn->customer->id)->firstOrFail();

        $data = [
            'sale_return' => $saleReturn,
            'customer' => $customer,
        ];

        return response()->view('admin.salesreturn.print', $data);
    }
}
