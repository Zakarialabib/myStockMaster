<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Finance\CalculateGrossMarginAction;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getRevenueKpis(Carbon $dateFrom, Carbon $dateTo): array
    {
        $cacheKey = 'kpi_revenue_' . $dateFrom->format('Ymd') . '_' . $dateTo->format('Ymd');

        return Cache::remember($cacheKey, 3600, function () use ($dateFrom, $dateTo): array {
            $salesData = Sale::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->selectRaw('SUM(total_amount) as total_revenue, COUNT(id) as total_sales')
                ->first();

            $totalRevenue = (float) ($salesData->total_revenue ?? 0);
            $totalSales = (int) ($salesData->total_sales ?? 0);

            $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

            $dailyRevenue = Sale::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->selectRaw('DATE(date) as sale_date, SUM(total_amount) as daily_total')
                ->groupBy('sale_date')
                ->oldest('sale_date')
                ->get();

            $averageDailyRevenue = $dailyRevenue->avg('daily_total') ?? 0;

            $topProducts = SaleDetails::query()->join('sales', 'sale_details.sale_id', '=', 'sales.id')
                ->join('products', 'sale_details.product_id', '=', 'products.id')
                ->whereBetween('sales.date', [$dateFrom, $dateTo])
                ->selectRaw('products.name, products.code, SUM(sale_details.price * sale_details.quantity) as total_revenue')
                ->groupBy('products.id', 'products.name', 'products.code')
                ->orderByDesc('total_revenue')
                ->limit(10)
                ->get();

            return [
                'total_revenue' => $totalRevenue,
                'total_sales' => $totalSales,
                'average_order_value' => $averageOrderValue,
                'average_daily_revenue' => $averageDailyRevenue,
                'daily_breakdown' => $dailyRevenue,
                'top_products' => $topProducts,
            ];
        });
    }

    public function getProfitabilityKpis(Carbon $dateFrom, Carbon $dateTo): array
    {
        $cacheKey = 'kpi_profitability_' . $dateFrom->format('Ymd') . '_' . $dateTo->format('Ymd');

        return Cache::remember($cacheKey, 3600, function () use ($dateFrom, $dateTo): array {
            $calculateGrossMarginAction = new CalculateGrossMarginAction;
            $grossMarginData = $calculateGrossMarginAction($dateFrom, $dateTo);

            $totalRevenue = Sale::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->sum('total_amount');

            $expensesData = Expense::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->selectRaw("SUM(amount) as total_expenses, SUM(CASE WHEN category NOT IN ('depreciation', 'interest') THEN amount ELSE 0 END) as operating_expenses")
                ->first();

            $totalExpenses = (float) ($expensesData->total_expenses ?? 0);
            $operatingExpenses = (float) ($expensesData->operating_expenses ?? 0);

            $netProfit = $totalRevenue - $totalExpenses;
            $netProfitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

            $ebitda = $totalRevenue - $operatingExpenses;
            $ebitdaMargin = $totalRevenue > 0 ? ($ebitda / $totalRevenue) * 100 : 0;

            return [
                'gross_margin' => $grossMarginData['gross_margin'] ?? 0,
                'gross_margin_percentage' => $grossMarginData['gross_margin_percentage'] ?? 0,
                'net_profit' => $netProfit,
                'net_profit_margin' => $netProfitMargin,
                'ebitda' => $ebitda,
                'ebitda_margin' => $ebitdaMargin,
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
            ];
        });
    }

    public function getEfficiencyKpis(Carbon $dateFrom, Carbon $dateTo): array
    {
        $cacheKey = 'kpi_efficiency_' . $dateFrom->format('Ymd') . '_' . $dateTo->format('Ymd');

        return Cache::remember($cacheKey, 3600, function () use ($dateFrom, $dateTo): array {
            $totalProducts = Product::query()->count();

            $efficiencyData = Sale::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->leftJoin('sale_details', 'sales.id', '=', 'sale_details.sale_id')
                ->selectRaw('COUNT(DISTINCT sales.id) as total_sales, COUNT(DISTINCT sale_details.product_id) as active_products')
                ->first();

            $totalSales = (int) ($efficiencyData->total_sales ?? 0);
            $activeSoldProducts = (int) ($efficiencyData->active_products ?? 0);

            $productTurnoverRate = $totalProducts > 0 ? ($activeSoldProducts / $totalProducts) * 100 : 0;

            $totalCogs = SaleDetails::query()->join('sales', 'sale_details.sale_id', '=', 'sales.id')
                ->join('products', 'sale_details.product_id', '=', 'products.id')
                ->whereBetween('sales.date', [$dateFrom, $dateTo])
                ->sum(DB::raw('sale_details.quantity * products.cost'));

            $averageInventoryValue = Product::query()->sum(DB::raw('stock * cost'));
            $inventoryTurnover = $averageInventoryValue > 0 ? $totalCogs / $averageInventoryValue : 0;

            $daysDiff = $dateFrom->diffInDays($dateTo) + 1;
            $salesPerDay = $daysDiff > 0 ? $totalSales / $daysDiff : 0;

            return [
                'total_sales' => $totalSales,
                'active_products' => $activeSoldProducts,
                'total_products' => $totalProducts,
                'product_turnover_rate' => $productTurnoverRate,
                'inventory_turnover' => $inventoryTurnover,
                'sales_per_day' => $salesPerDay,
                'average_inventory_value' => $averageInventoryValue,
            ];
        });
    }

    public function getGrowthKpis(Carbon $dateFrom, Carbon $dateTo): array
    {
        $cacheKey = 'kpi_growth_' . $dateFrom->format('Ymd') . '_' . $dateTo->format('Ymd');

        return Cache::remember($cacheKey, 3600, function () use ($dateFrom, $dateTo): array {
            $currentRevenue = Sale::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->sum('total_amount');

            $currentSales = Sale::query()->whereBetween('date', [$dateFrom, $dateTo])
                ->count();

            $periodDays = $dateFrom->diffInDays($dateTo);
            $previousDateFrom = $dateFrom->copy()->subDays($periodDays + 1);
            $previousDateTo = $dateFrom->copy()->subDay();

            $previousRevenue = Sale::query()->whereBetween('date', [$previousDateFrom, $previousDateTo])
                ->sum('total_amount');

            $previousSales = Sale::query()->whereBetween('date', [$previousDateFrom, $previousDateTo])
                ->count();

            $yearAgoDateFrom = $dateFrom->copy()->subYear();
            $yearAgoDateTo = $dateTo->copy()->subYear();

            $yearAgoRevenue = Sale::query()->whereBetween('date', [$yearAgoDateFrom, $yearAgoDateTo])
                ->sum('total_amount');

            $yearAgoSales = Sale::query()->whereBetween('date', [$yearAgoDateFrom, $yearAgoDateTo])
                ->count();

            $revenueGrowth = $previousRevenue > 0
                ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100
                : 0;

            $salesGrowth = $previousSales > 0
                ? (($currentSales - $previousSales) / $previousSales) * 100
                : 0;

            $yearOverYearRevenueGrowth = $yearAgoRevenue > 0
                ? (($currentRevenue - $yearAgoRevenue) / $yearAgoRevenue) * 100
                : 0;

            $yearOverYearSalesGrowth = $yearAgoSales > 0
                ? (($currentSales - $yearAgoSales) / $yearAgoSales) * 100
                : 0;

            return [
                'current_revenue' => $currentRevenue,
                'previous_revenue' => $previousRevenue,
                'year_ago_revenue' => $yearAgoRevenue,
                'revenue_growth' => $revenueGrowth,
                'year_over_year_revenue_growth' => $yearOverYearRevenueGrowth,
                'current_sales' => $currentSales,
                'previous_sales' => $previousSales,
                'year_ago_sales' => $yearAgoSales,
                'sales_growth' => $salesGrowth,
                'year_over_year_sales_growth' => $yearOverYearSalesGrowth,
            ];
        });
    }
}
