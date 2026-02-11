<?php

declare(strict_types=1);

namespace App\Livewire\Finance;

use App\Actions\Finance\CalculateGrossMarginAction;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Exception;

#[Title('KPI Tracking')]
#[Layout('layouts.app')]
class KpiTracking extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $kpiType = 'revenue';
    public $comparisonPeriod = 'previous';
    public $kpiData = [];
    public $comparisonData = [];
    public $loading = false;
    public $autoRefresh = false;
    public $refreshInterval = 60; // seconds

    protected $rules = [
        'dateFrom'         => 'required|date',
        'dateTo'           => 'required|date|after_or_equal:dateFrom',
        'kpiType'          => 'required|in:revenue,profitability,efficiency,growth',
        'comparisonPeriod' => 'required|in:previous,year_ago,custom',
    ];

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadKpiData();
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->loadKpiData();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->loadKpiData();
    }

    public function updatedKpiType()
    {
        $this->validateOnly('kpiType');
        $this->loadKpiData();
    }

    public function updatedComparisonPeriod()
    {
        $this->validateOnly('comparisonPeriod');
        $this->loadKpiData();
    }

    public function loadKpiData()
    {
        $this->loading = true;

        try {
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            switch ($this->kpiType) {
                case 'revenue':
                    $this->kpiData = $this->calculateRevenueKpis($dateFrom, $dateTo);

                    break;
                case 'profitability':
                    $this->kpiData = $this->calculateProfitabilityKpis($dateFrom, $dateTo);

                    break;
                case 'efficiency':
                    $this->kpiData = $this->calculateEfficiencyKpis($dateFrom, $dateTo);

                    break;
                case 'growth':
                    $this->kpiData = $this->calculateGrowthKpis($dateFrom, $dateTo);

                    break;
            }

            // Load comparison data
            $this->loadComparisonData($dateFrom, $dateTo);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load KPI data: '.$e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    private function calculateRevenueKpis($dateFrom, $dateTo)
    {
        $totalRevenue = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->sum('total_amount');

        $totalSales = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->count();

        $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        $dailyRevenue = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('DATE(date) as sale_date, SUM(total_amount) as daily_total')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        $averageDailyRevenue = $dailyRevenue->avg('daily_total') ?? 0;

        // Top selling products by revenue
        $topProducts = SaleDetails::join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [$dateFrom, $dateTo])
            ->selectRaw('products.name, products.code, SUM(sale_details.price * sale_details.quantity) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return [
            'total_revenue'         => $totalRevenue,
            'total_sales'           => $totalSales,
            'average_order_value'   => $averageOrderValue,
            'average_daily_revenue' => $averageDailyRevenue,
            'daily_breakdown'       => $dailyRevenue,
            'top_products'          => $topProducts,
        ];
    }

    private function calculateProfitabilityKpis($dateFrom, $dateTo)
    {
        $grossMarginAction = new CalculateGrossMarginAction();
        $grossMarginData = $grossMarginAction($dateFrom, $dateTo);

        $totalRevenue = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->sum('total_amount');

        $totalExpenses = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->sum('amount');

        $netProfit = $totalRevenue - $totalExpenses;
        $netProfitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // Calculate EBITDA (simplified)
        $operatingExpenses = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->where('category', '!=', 'depreciation')
            ->where('category', '!=', 'interest')
            ->sum('amount');

        $ebitda = $totalRevenue - $operatingExpenses;
        $ebitdaMargin = $totalRevenue > 0 ? ($ebitda / $totalRevenue) * 100 : 0;

        return [
            'gross_margin'            => $grossMarginData['gross_margin'] ?? 0,
            'gross_margin_percentage' => $grossMarginData['gross_margin_percentage'] ?? 0,
            'net_profit'              => $netProfit,
            'net_profit_margin'       => $netProfitMargin,
            'ebitda'                  => $ebitda,
            'ebitda_margin'           => $ebitdaMargin,
            'total_revenue'           => $totalRevenue,
            'total_expenses'          => $totalExpenses,
        ];
    }

    private function calculateEfficiencyKpis($dateFrom, $dateTo)
    {
        $totalSales = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->count();

        $totalProducts = Product::count();
        $activeSoldProducts = SaleDetails::join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereBetween('sales.date', [$dateFrom, $dateTo])
            ->distinct('sale_details.product_id')
            ->count();

        $productTurnoverRate = $totalProducts > 0 ? ($activeSoldProducts / $totalProducts) * 100 : 0;

        // Inventory turnover (simplified)
        $totalCogs = SaleDetails::join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [$dateFrom, $dateTo])
            ->sum(DB::raw('sale_details.quantity * products.cost'));

        $averageInventoryValue = Product::sum(DB::raw('stock * cost'));
        $inventoryTurnover = $averageInventoryValue > 0 ? $totalCogs / $averageInventoryValue : 0;

        // Sales per day
        $daysDiff = $dateFrom->diffInDays($dateTo) + 1;
        $salesPerDay = $daysDiff > 0 ? $totalSales / $daysDiff : 0;

        return [
            'total_sales'             => $totalSales,
            'active_products'         => $activeSoldProducts,
            'total_products'          => $totalProducts,
            'product_turnover_rate'   => $productTurnoverRate,
            'inventory_turnover'      => $inventoryTurnover,
            'sales_per_day'           => $salesPerDay,
            'average_inventory_value' => $averageInventoryValue,
        ];
    }

    private function calculateGrowthKpis($dateFrom, $dateTo)
    {
        $currentRevenue = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->sum('total_amount');

        $currentSales = Sale::whereBetween('date', [$dateFrom, $dateTo])
            ->count();

        // Calculate previous period
        $periodDays = $dateFrom->diffInDays($dateTo);
        $previousDateFrom = $dateFrom->copy()->subDays($periodDays + 1);
        $previousDateTo = $dateFrom->copy()->subDay();

        $previousRevenue = Sale::whereBetween('date', [$previousDateFrom, $previousDateTo])
            ->sum('total_amount');

        $previousSales = Sale::whereBetween('date', [$previousDateFrom, $previousDateTo])
            ->count();

        // Calculate year-over-year
        $yearAgoDateFrom = $dateFrom->copy()->subYear();
        $yearAgoDateTo = $dateTo->copy()->subYear();

        $yearAgoRevenue = Sale::whereBetween('date', [$yearAgoDateFrom, $yearAgoDateTo])
            ->sum('total_amount');

        $yearAgoSales = Sale::whereBetween('date', [$yearAgoDateFrom, $yearAgoDateTo])
            ->count();

        // Calculate growth rates
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
            'current_revenue'               => $currentRevenue,
            'previous_revenue'              => $previousRevenue,
            'year_ago_revenue'              => $yearAgoRevenue,
            'revenue_growth'                => $revenueGrowth,
            'year_over_year_revenue_growth' => $yearOverYearRevenueGrowth,
            'current_sales'                 => $currentSales,
            'previous_sales'                => $previousSales,
            'year_ago_sales'                => $yearAgoSales,
            'sales_growth'                  => $salesGrowth,
            'year_over_year_sales_growth'   => $yearOverYearSalesGrowth,
        ];
    }

    private function loadComparisonData($dateFrom, $dateTo)
    {
        $periodDays = $dateFrom->diffInDays($dateTo);

        switch ($this->comparisonPeriod) {
            case 'previous':
                $comparisonDateFrom = $dateFrom->copy()->subDays($periodDays + 1);
                $comparisonDateTo = $dateFrom->copy()->subDay();

                break;
            case 'year_ago':
                $comparisonDateFrom = $dateFrom->copy()->subYear();
                $comparisonDateTo = $dateTo->copy()->subYear();

                break;
            default:
                return;
        }

        switch ($this->kpiType) {
            case 'revenue':
                $this->comparisonData = $this->calculateRevenueKpis($comparisonDateFrom, $comparisonDateTo);

                break;
            case 'profitability':
                $this->comparisonData = $this->calculateProfitabilityKpis($comparisonDateFrom, $comparisonDateTo);

                break;
            case 'efficiency':
                $this->comparisonData = $this->calculateEfficiencyKpis($comparisonDateFrom, $comparisonDateTo);

                break;
            case 'growth':
                $this->comparisonData = $this->calculateGrowthKpis($comparisonDateFrom, $comparisonDateTo);

                break;
        }
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = ! $this->autoRefresh;

        if ($this->autoRefresh) {
            $this->dispatch('start-auto-refresh', interval: $this->refreshInterval * 1000);
        } else {
            $this->dispatch('stop-auto-refresh');
        }
    }

    public function refreshKpis()
    {
        $this->loadKpiData();
        $this->dispatch('kpi-data-refreshed');
    }

    public function exportKpiData()
    {
        try {
            $filename = 'kpi_'.$this->kpiType.'_'.now()->format('Y-m-d_H-i-s').'.json';

            return response()->streamDownload(function () {
                echo json_encode([
                    'kpi_type'   => $this->kpiType,
                    'date_range' => [
                        'from' => $this->dateFrom,
                        'to'   => $this->dateTo,
                    ],
                    'comparison_period' => $this->comparisonPeriod,
                    'current_data'      => $this->kpiData,
                    'comparison_data'   => $this->comparisonData,
                    'generated_at'      => now()->toISOString(),
                ], JSON_PRETTY_PRINT);
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to export KPI data: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.finance.kpi-tracking');
    }
}
