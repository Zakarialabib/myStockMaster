<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class ExportSaleController extends Controller
{
    #[Get('/admin/sales/pdf/{id}', name: 'sales.pdf')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(int|string $id): Response
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $customer = Customer::where('id', $sale->customer->id)->firstOrFail();

        $data = [
            'sale' => $sale,
            'customer' => $customer,
            'logo' => $this->getCompanyLogo(),
        ];

        $pdf = PDF::loadView('admin.sale.print', $data, [], [
            'format' => 'a4',
            'watermark' => $this->setWaterMark($sale),
        ]);

        return $pdf->stream(__('Sale') . $sale->reference . '.pdf');
    }

    private function getCompanyLogo(): string
    {
        return 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo.png')));
    }

    private function setWaterMark(Sale $model): string
    {
        return $model && $model->status ? (string) $model->status : '';
    }
}
