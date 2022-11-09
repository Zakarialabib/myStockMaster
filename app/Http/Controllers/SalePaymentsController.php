<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Sale;
use App\Models\SalePayment;

class SalePaymentsController extends Controller
{

    public function index($sale_id) {
        abort_if(Gate::denies('access_sale_payments'), 403);

        $sale = Sale::findOrFail($sale_id);

        return view('admin.sale.payments.index', compact('sale'));
    }


}
