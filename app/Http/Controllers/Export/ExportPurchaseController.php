<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class ExportPurchaseController extends Controller
{
    #[Get('/admin/purchases/purchases/pdf/{id}', name: 'purchases.pdf')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(int|string $id): Response
    {
        $purchase = Purchase::with('supplier', 'purchaseDetails')->where('id', $id)->firstOrFail();
        $supplier = Supplier::where('id', $purchase->supplier->id)->firstOrFail();

        $data = [
            'purchase' => $purchase,
            'supplier' => $supplier,
        ];

        $pdf = PDF::loadView('admin.purchases.print', $data, [], [
            'format' => 'a5',
        ]);

        return $pdf->stream(__('Purchase') . $purchase->reference . '.pdf');
    }
}
