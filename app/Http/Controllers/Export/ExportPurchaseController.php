<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Response;

class ExportPurchaseController extends Controller
{
    public function __invoke(int|string $id): Response
    {
        $purchase = Purchase::with('supplier', 'purchaseDetails')->where('id', $id)->firstOrFail();
        $supplier = Supplier::query()->where('id', $purchase->supplier->id)->firstOrFail();

        $data = [
            'purchase' => $purchase,
            'supplier' => $supplier,
        ];

        return response()->view('admin.purchases.print', $data);
    }
}
