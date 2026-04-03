<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SaleReturn;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Delete;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SalesReturnController extends Controller
{
    #[Get('/admin/sale-returns/{sale_return}', name: 'sale-returns.show')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function show(SaleReturn $sale_return): View
    {
        abort_if(Gate::denies('show_sale_returns'), 403);

        $customer = Customer::findOrFail($sale_return->customer_id);

        return view('admin.salesreturn.show', compact('sale_return', 'customer'));
    }

    #[Delete('/admin/sale-returns/{sale_return}', name: 'sale-returns.destroy')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function destroy(SaleReturn $sale_return): RedirectResponse
    {
        abort_if(Gate::denies('sale_return_delete'), 403);

        $sale_return->delete();

        // toast('Sale Return Deleted!', 'warning');

        return redirect()->route('sale-returns.index');
    }
}
