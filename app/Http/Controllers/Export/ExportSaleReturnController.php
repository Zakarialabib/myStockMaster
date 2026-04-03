<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SaleReturn;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class ExportSaleReturnController extends Controller
{
    #[Get('/admin/sale-returns/pdf/{id}', name: 'sale-returns.pdf')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(int|string $id): Response
    {
        $saleReturn = SaleReturn::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $saleReturn->customer->id)->firstOrFail();

        $data = [
            'sale_return' => $saleReturn,
            'customer' => $customer,
        ];

        $pdf = PDF::loadView('admin.salesreturn.print', $data);

        return $pdf->stream(__('Sale Return') . $saleReturn->reference . '.pdf');
    }
}
