<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PurchaseStatus;
use App\Enums\SaleStatus;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturnPayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturnPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function currentMonthChart(): JsonResponse
    {
        abort_if(! request()->ajax(), 404);

        $currentMonthSales = Sale::whereStatus('Completed')->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->sum('total_amount') / 100;
        $currentMonthPurchases = Purchase::whereStatus('Completed')->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->sum('total_amount') / 100;
        $currentMonthExpenses = Expense::query()->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->sum('amount') / 100;

        return new \Illuminate\Http\JsonResponse([
            'sales' => $currentMonthSales,
            'purchases' => $currentMonthPurchases,
            'expenses' => $currentMonthExpenses,
        ]);
    }

    public function salesPurchasesChart(): JsonResponse
    {
        abort_if(! request()->ajax(), 404);

        $jsonResponse = $this->salesChartData();
        $purchases = $this->purchasesChartData();

        return new \Illuminate\Http\JsonResponse(['sales' => $jsonResponse, 'purchases' => $purchases]);
    }

    public function paymentChart(): JsonResponse
    {
        abort_if(! request()->ajax(), 404);

        $dates = collect();

        foreach (range(-11, 0) as $i) {
            $date = \Illuminate\Support\Facades\Date::now()->addMonths($i)->format('m-Y');
            $dates->put($date, 0);
        }

        $date_range = \Illuminate\Support\Facades\Date::today()->subYear()->format('Y-m-d');

        $sale_payments = SalePayment::query()->where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw('SUM(amount) as amount'),
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $sale_return_payments = SaleReturnPayment::query()->where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw('SUM(amount) as amount'),
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $purchase_payments = PurchasePayment::query()->where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw('SUM(amount) as amount'),
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $purchase_return_payments = PurchaseReturnPayment::query()->where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw('SUM(amount) as amount'),
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $expenses = Expense::query()->where('date', '>=', $date_range)
            ->select([
                DB::raw("DATE_FORMAT(date, '%m-%Y') as month"),
                DB::raw('SUM(amount) as amount'),
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $payment_received = array_merge_numeric_values($sale_payments, $purchase_return_payments);
        $payment_sent = array_merge_numeric_values($purchase_payments, $sale_return_payments, $expenses);

        $dates_received = $dates->merge($payment_received);
        $dates_sent = $dates->merge($payment_sent);

        $received_payments = [];
        $sent_payments = [];
        $months = [];

        foreach ($dates_received as $key => $value) {
            $received_payments[] = $value;
            $months[] = $key;
        }

        foreach ($dates_sent as $date_sent) {
            $sent_payments[] = $date_sent;
        }

        return new \Illuminate\Http\JsonResponse([
            'payment_sent' => $sent_payments,
            'payment_received' => $received_payments,
            'months' => $months,
        ]);
    }

    public function salesChartData(): JsonResponse
    {
        $dates = collect();

        foreach (range(-6, 0) as $i) {
            $date = \Illuminate\Support\Facades\Date::now()->addDays($i)->format('d-m-y');
            $dates->put($date, 0);
        }

        $date_range = \Illuminate\Support\Facades\Date::today()->subDays(6);

        $sales = Sale::whereStatus(SaleStatus::COMPLETED)
            ->where('date', '>=', $date_range)
            ->groupBy(DB::raw("DATE_FORMAT(date,'%d-%m-%y')"))
            ->orderBy('date')
            ->get([
                DB::raw(DB::raw("DATE_FORMAT(date,'%d-%m-%y') as date")),
                DB::raw('SUM(total_amount) AS count'),
            ])
            ->pluck('count', 'date');

        $dates = $dates->merge($sales);

        $data = [];
        $days = [];

        foreach ($dates as $key => $value) {
            $data[] = $value / 100;
            $days[] = $key;
        }

        return new \Illuminate\Http\JsonResponse(['data' => $data, 'days' => $days]);
    }

    public function purchasesChartData(): JsonResponse
    {
        $dates = collect();

        foreach (range(-6, 0) as $i) {
            $date = \Illuminate\Support\Facades\Date::now()->addDays($i)->format('d-m-y');
            $dates->put($date, 0);
        }

        $date_range = \Illuminate\Support\Facades\Date::today()->subDays(6);

        $purchases = Purchase::whereStatus(PurchaseStatus::COMPLETED)
            ->where('date', '>=', $date_range)
            ->groupBy(DB::raw("DATE_FORMAT(date,'%d-%m-%y')"))
            ->orderBy('date')
            ->get([
                DB::raw(DB::raw("DATE_FORMAT(date,'%d-%m-%y') as date")),
                DB::raw('SUM(total_amount) AS count'),
            ])
            ->pluck('count', 'date');

        $dates = $dates->merge($purchases);

        $data = [];
        $days = [];

        foreach ($dates as $key => $value) {
            $data[] = $value / 100;
            $days[] = $key;
        }

        return new \Illuminate\Http\JsonResponse(['data' => $data, 'days' => $days]);
    }

    public function changeLanguage(string $locale): RedirectResponse
    {
        Cookie::queue('lang', $locale);

        return back();
    }
}
