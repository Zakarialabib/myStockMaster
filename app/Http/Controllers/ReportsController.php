<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ReportsController extends Controller
{
    public function profitLossReport()
    {
        abort_if(Gate::denies('report_access'), 403);

        return view('admin.reports.profit-loss.index');
    }

    public function paymentsReport()
    {
        abort_if(Gate::denies('report_access'), 403);

        return view('admin.reports.payments.index');
    }

    public function salesReport()
    {
        abort_if(Gate::denies('report_access'), 403);

        $customers = Customer::select(['id', 'name'])->get();

        return view('admin.reports.sales.index', compact('customers'));
    }

    public function purchasesReport()
    {
        abort_if(Gate::denies('report_access'), 403);

        return view('admin.reports.purchases.index');
    }

    public function salesReturnReport()
    {
        abort_if(Gate::denies('report_access'), 403);

        return view('admin.reports.sales-return.index');
    }

    public function purchasesReturnReport()
    {
        abort_if(Gate::denies('report_access'), 403);

        return view('admin.reports.purchases-return.index');
    }
}
