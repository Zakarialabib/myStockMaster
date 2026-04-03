<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Delete;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class PurchasesReturnController extends Controller
{
    #[Get('/admin/purchase-returns/{purchase_return}', name: 'purchase-returns.show')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function show(PurchaseReturn $purchase_return): View
    {
        abort_if(Gate::denies('purchase_return_show'), 403);

        $supplier = Supplier::findOrFail($purchase_return->supplier_id);

        return view('admin.purchasesreturn.show', compact('purchase_return', 'supplier'));
    }

    #[Delete('/admin/purchase-returns/{purchase_return}', name: 'purchase-returns.destroy')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function destroy(PurchaseReturn $purchase_return): RedirectResponse
    {
        abort_if(Gate::denies('purchase_return_delete'), 403);

        $purchase_return->delete();

        // toast('Purchase Return Deleted!', 'warning');

        return redirect()->route('purchase-returns.index');
    }
}
