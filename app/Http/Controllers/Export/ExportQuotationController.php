<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class ExportQuotationController extends Controller
{
    #[Get('/admin/quotations/pdf/{id}', name: 'quotations.pdf')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(int|string $id): Response
    {
        $quotation = Quotation::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $quotation->customer->id)->firstOrFail();

        $data = [
            'quotation' => $quotation,
            'customer' => $customer,
        ];

        $pdf = PDF::loadView('admin.quotation.print', $data);

        return $pdf->stream(__('Quotation') . $quotation->reference . '.pdf');
    }
}
