<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Response;

class ExportSaleController extends Controller
{
    public function __invoke(int|string $id): Response
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $customer = Customer::where('id', $sale->customer->id)->firstOrFail();

        $data = [
            'sale' => $sale,
            'customer' => $customer,
            'logo' => $this->getCompanyLogo(),
        ];

        return response()->view('admin.sale.print', $data);
    }

    private function getCompanyLogo(): string
    {
        return 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo.png')));
    }
}
