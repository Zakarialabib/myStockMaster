<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SalePaymentsController extends Controller
{
    public function index($sale_id)
    {
        abort_if(Gate::denies('access_sale_payments'), 403);

        $sale = Sale::findOrFail($sale_id);

        return view('admin.sale.payments.index', compact('sale'));
    }
}
