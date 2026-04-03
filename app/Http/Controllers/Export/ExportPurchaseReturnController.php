<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class ExportPurchaseReturnController extends Controller
{
    #[Get('/admin/purchase-returns/pdf/{id}', name: 'purchase-returns.pdf')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(int|string $id): Response
    {
        $purchaseReturn = PurchaseReturn::where('id', $id)->firstOrFail();
        $supplier = Supplier::where('id', $purchaseReturn->supplier->id)->firstOrFail();

        $data = [
            'purchase_return' => $purchaseReturn,
            'supplier' => $supplier,
        ];

        $pdf = PDF::loadView('admin.purchasesreturn.print', $data);

        return $pdf->stream(__('Purchase Return') . $purchaseReturn->reference . '.pdf');
    }
}
