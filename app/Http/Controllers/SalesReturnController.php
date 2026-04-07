<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SaleReturn;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SalesReturnController extends Controller
{
    public function show(SaleReturn $saleReturn): View
    {
        abort_if(Gate::denies('show_sale_returns'), 403);

        $customer = Customer::query()->findOrFail($saleReturn->customer_id);

        return view('admin.salesreturn.show', ['sale_return' => $sale_return, 'customer' => $customer]);
    }

    public function destroy(SaleReturn $saleReturn): RedirectResponse
    {
        abort_if(Gate::denies('sale_return_delete'), 403);

        $saleReturn->delete();

        // toast('Sale Return Deleted!', 'warning');

        return to_route('sale-returns.index');
    }
}
