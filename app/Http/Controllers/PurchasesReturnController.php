<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class PurchasesReturnController extends Controller
{
    public function show(PurchaseReturn $purchaseReturn): View
    {
        abort_if(Gate::denies('purchase_return_show'), 403);

        $supplier = Supplier::query()->findOrFail($purchaseReturn->supplier_id);

        return view('admin.purchasesreturn.show', ['purchase_return' => $purchase_return, 'supplier' => $supplier]);
    }

    public function destroy(PurchaseReturn $purchaseReturn): RedirectResponse
    {
        abort_if(Gate::denies('purchase_return_delete'), 403);

        $purchaseReturn->delete();

        // toast('Purchase Return Deleted!', 'warning');

        return to_route('purchase-returns.index');
    }
}
