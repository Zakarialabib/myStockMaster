<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ReportsController extends Controller
{
    public function profitLossReport(): RedirectResponse
    {
        abort_if(Gate::denies('report_access'), 403);

        return to_route('profit-loss-report.index');
    }

    public function paymentsReport(): RedirectResponse
    {
        abort_if(Gate::denies('report_access'), 403);

        return to_route('payments-report.index');
    }

    public function salesReport(): RedirectResponse
    {
        abort_if(Gate::denies('report_access'), 403);

        return to_route('sales-report.index');
    }

    public function purchasesReport(): RedirectResponse
    {
        abort_if(Gate::denies('report_access'), 403);

        return to_route('purchases-report.index');
    }

    public function salesReturnReport(): RedirectResponse
    {
        abort_if(Gate::denies('report_access'), 403);

        return to_route('sales-return-report.index');
    }

    public function purchasesReturnReport(): RedirectResponse
    {
        abort_if(Gate::denies('report_access'), 403);

        return to_route('purchases-return-report.index');
    }
}
