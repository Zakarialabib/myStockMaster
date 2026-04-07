<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Illuminate\Http\Response;

class ExportPurchaseReturnController extends Controller
{
    public function __invoke(int|string $id): Response
    {
        $purchaseReturn = PurchaseReturn::query()->where('id', $id)->firstOrFail();
        $supplier = Supplier::query()->where('id', $purchaseReturn->supplier->id)->firstOrFail();

        $data = [
            'purchase_return' => $purchaseReturn,
            'supplier' => $supplier,
        ];

        return response()->view('admin.purchasesreturn.print', $data);
    }
}
