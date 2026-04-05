<?php

declare(strict_types=1);

namespace App\Livewire\Finance;

use App\Actions\Finance\CalculateBreakEvenAction;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BreakEvenAnalysis extends Component
{
    #[Validate('required|date')]
    public string $dateFrom;

    #[Validate('required|date|after_or_equal:dateFrom')]
    public string $dateTo;

    #[Validate('required|in:overall,product,scenario')]
    public string $analysisType = 'overall';

    #[Validate('nullable|exists:products,id')]
    public ?int $selectedProduct = null;

    public array $breakEvenData = [];

    public array $scenarioAnalysis = [];

    public bool $loading = false;

    // Scenario planning inputs
    #[Validate('numeric|min:-100|max:100')]
    public float|int $fixedCostAdjustment = 0;

    #[Validate('numeric|min:-100|max:100')]
    public float|int $variableCostAdjustment = 0;

    #[Validate('numeric|min:-100|max:100')]
    public float|int $priceAdjustment = 0;

    #[Validate('numeric|min:0')]
    public float|int $targetProfit = 0;

    public function mount()
    {
        $this->dateFrom = now()->subDays(90)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadBreakEvenAnalysis();
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->loadBreakEvenAnalysis();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->loadBreakEvenAnalysis();
    }

    public function updatedAnalysisType()
    {
        $this->validateOnly('analysisType');
        $this->loadBreakEvenAnalysis();
    }

    public function updatedSelectedProduct()
    {
        if ($this->analysisType === 'product') {
            $this->loadBreakEvenAnalysis();
        }
    }

    public function updatedFixedCostAdjustment()
    {
        $this->validateOnly('fixedCostAdjustment');

        if ($this->analysisType === 'scenario') {
            $this->runScenarioAnalysis();
        }
    }

    public function updatedVariableCostAdjustment()
    {
        $this->validateOnly('variableCostAdjustment');

        if ($this->analysisType === 'scenario') {
            $this->runScenarioAnalysis();
        }
    }

    public function updatedPriceAdjustment()
    {
        $this->validateOnly('priceAdjustment');

        if ($this->analysisType === 'scenario') {
            $this->runScenarioAnalysis();
        }
    }

    public function updatedTargetProfit()
    {
        $this->validateOnly('targetProfit');

        if ($this->analysisType === 'scenario') {
            $this->runScenarioAnalysis();
        }
    }

    public function loadBreakEvenAnalysis()
    {
        $this->loading = true;

        try {
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            switch ($this->analysisType) {
                case 'overall':
                    $this->loadOverallBreakEven($dateFrom, $dateTo);

                    break;
                case 'product':
                    $this->loadProductBreakEven($dateFrom, $dateTo);

                    break;
                case 'scenario':
                    $this->loadOverallBreakEven($dateFrom, $dateTo);
                    $this->runScenarioAnalysis();

                    break;
            }
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load break-even analysis: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    private function loadOverallBreakEven($dateFrom, $dateTo)
    {
        $breakEvenAction = new CalculateBreakEvenAction;

        // Get actual fixed costs for the period
        $fixedCosts = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->whereIn('category', ['rent', 'salaries', 'utilities', 'insurance', 'equipment_lease'])
            ->select('category', 'amount')
            ->get()
            ->groupBy('category')
            ->map->sum('amount')
            ->toArray();

        $this->breakEvenData = $breakEvenAction($fixedCosts, $dateTo);

        // Add additional analysis
        $this->breakEvenData['analysis'] = $this->calculateBreakEven($dateFrom, $dateTo);
    }

    private function loadProductBreakEven($dateFrom, $dateTo)
    {
        if (! $this->selectedProduct) {
            $this->breakEvenData = [];

            return;
        }

        $product = Product::findOrFail($this->selectedProduct);

        // Calculate product-specific break-even
        $productSales = SaleDetails::join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sale_details.product_id', $this->selectedProduct)
            ->whereBetween('sales.date', [$dateFrom, $dateTo])
            ->sum('sale_details.quantity');

        $productRevenue = SaleDetails::join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sale_details.product_id', $this->selectedProduct)
            ->whereBetween('sales.date', [$dateFrom, $dateTo])
            ->sum(DB::raw('sale_details.price * sale_details.quantity'));

        $averageSellingPrice = $productSales > 0 ? $productRevenue / $productSales : $product->price;
        $variableCostPerUnit = $product->cost;
        $contributionMargin = $averageSellingPrice - $variableCostPerUnit;
        $contributionMarginRatio = $averageSellingPrice > 0 ? ($contributionMargin / $averageSellingPrice) * 100 : 0;

        // Allocate fixed costs (simplified - equal allocation)
        $totalProducts = Product::count();
        $totalFixedCosts = Expense::whereBetween('date', [$dateFrom, $dateTo])
            ->sum('amount');

        $allocatedFixedCosts = $totalProducts > 0 ? $totalFixedCosts / $totalProducts : 0;

        $breakEvenUnits = $contributionMargin > 0 ? $allocatedFixedCosts / $contributionMargin : 0;
        $breakEvenRevenue = $breakEvenUnits * $averageSellingPrice;

        $this->breakEvenData = [
            'product' => $product,
            'current_sales_units' => $productSales,
            'current_revenue' => $productRevenue,
            'average_selling_price' => $averageSellingPrice,
            'variable_cost_per_unit' => $variableCostPerUnit,
            'contribution_margin' => $contributionMargin,
            'contribution_margin_ratio' => $contributionMarginRatio,
            'allocated_fixed_costs' => $allocatedFixedCosts,
            'break_even_units' => $breakEvenUnits,
            'break_even_revenue' => $breakEvenRevenue,
            'units_above_break_even' => max(0, $productSales - $breakEvenUnits),
            'safety_margin_units' => $productSales - $breakEvenUnits,
            'safety_margin_percentage' => $productSales > 0 ? (($productSales - $breakEvenUnits) / $productSales) * 100 : 0,
        ];
    }

    private function calculateBreakEven($dateFrom, $dateTo)
    {
        return \Illuminate\Support\Facades\Cache::remember('break_even_' . $this->analysisType . '_' . $this->dateFrom . '_' . $this->dateTo, 3600, function () use ($dateFrom, $dateTo) {
            $metrics = Sale::whereBetween('date', [$dateFrom, $dateTo])
                ->selectRaw('COALESCE(SUM(total_amount), 0) as total_revenue, COUNT(*) as total_sales')
                ->first();

            $totalRevenue = (float) $metrics->total_revenue;
            $totalSales = (int) $metrics->total_sales;

            $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

            // Calculate margin of safety
            $breakEvenRevenue = $this->breakEvenData['break_even_revenue'] ?? 0;
            $marginOfSafety = $totalRevenue - $breakEvenRevenue;
            $marginOfSafetyPercentage = $totalRevenue > 0 ? ($marginOfSafety / $totalRevenue) * 100 : 0;

            // Calculate degree of operating leverage
            $contributionMargin = $this->breakEvenData['total_contribution_margin'] ?? 0;
            $operatingIncome = $contributionMargin - ($this->breakEvenData['total_fixed_costs'] ?? 0);
            $degreeOfOperatingLeverage = $operatingIncome != 0 ? $contributionMargin / $operatingIncome : 0;

            return [
                'total_revenue' => $totalRevenue,
                'total_sales' => $totalSales,
                'average_order_value' => $averageOrderValue,
                'margin_of_safety' => $marginOfSafety,
                'margin_of_safety_percentage' => $marginOfSafetyPercentage,
                'degree_of_operating_leverage' => $degreeOfOperatingLeverage,
            ];
        });
    }

    public function runScenarioAnalysis()
    {
        if (empty($this->breakEvenData)) {
            return;
        }

        $baseFixedCosts = $this->breakEvenData['total_fixed_costs'] ?? 0;
        $baseVariableCostRatio = $this->breakEvenData['variable_cost_ratio'] ?? 0;
        $baseAveragePrice = $this->breakEvenData['average_selling_price'] ?? 0;

        // Apply adjustments
        $adjustedFixedCosts = $baseFixedCosts * (1 + $this->fixedCostAdjustment / 100);
        $adjustedVariableCostRatio = $baseVariableCostRatio * (1 + $this->variableCostAdjustment / 100);
        $adjustedAveragePrice = $baseAveragePrice * (1 + $this->priceAdjustment / 100);

        // Calculate new break-even
        $adjustedContributionMarginRatio = 1 - $adjustedVariableCostRatio;
        $adjustedBreakEvenRevenue = $adjustedContributionMarginRatio > 0
            ? ($adjustedFixedCosts + $this->targetProfit) / $adjustedContributionMarginRatio
            : 0;

        $adjustedBreakEvenUnits = $adjustedAveragePrice > 0
            ? $adjustedBreakEvenRevenue / $adjustedAveragePrice
            : 0;

        // Calculate target revenue for desired profit
        $targetRevenueForProfit = $adjustedContributionMarginRatio > 0
            ? ($adjustedFixedCosts + $this->targetProfit) / $adjustedContributionMarginRatio
            : 0;

        $this->scenarioAnalysis = [
            'adjusted_fixed_costs' => $adjustedFixedCosts,
            'adjusted_variable_cost_ratio' => $adjustedVariableCostRatio,
            'adjusted_average_price' => $adjustedAveragePrice,
            'adjusted_contribution_margin_ratio' => $adjustedContributionMarginRatio,
            'adjusted_break_even_revenue' => $adjustedBreakEvenRevenue,
            'adjusted_break_even_units' => $adjustedBreakEvenUnits,
            'target_revenue_for_profit' => $targetRevenueForProfit,
            'revenue_change_vs_base' => $this->breakEvenData['break_even_revenue'] > 0
                ? (($adjustedBreakEvenRevenue - $this->breakEvenData['break_even_revenue']) / $this->breakEvenData['break_even_revenue']) * 100
                : 0,
        ];
    }

    public function resetScenario()
    {
        $this->fixedCostAdjustment = 0;
        $this->variableCostAdjustment = 0;
        $this->priceAdjustment = 0;
        $this->targetProfit = 0;
        $this->scenarioAnalysis = [];
    }

    public function exportAnalysis()
    {
        try {
            $filename = 'break_even_analysis_' . $this->analysisType . '_' . now()->format('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->streamDownload(function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Metric', 'Value']);

                foreach ($this->breakEvenData as $key => $value) {
                    if (is_scalar($value)) {
                        fputcsv($file, [$key, $value]);
                    }
                }

                fclose($file);
            }, $filename, $headers);

        } catch (Exception $e) {
            session()->flash('error', 'Error exporting data: ' . $e->getMessage());

            return;
        }
    }

    #[Computed]
    public function chartData(): array
    {
        if ($this->analysisType === 'scenario' && ! empty($this->scenarioAnalysis)) {
            return $this->getScenarioChartData();
        }

        if (empty($this->breakEvenData)) {
            return [];
        }

        $breakEvenRevenue = $this->breakEvenData['break_even_revenue'] ?? 0;
        $fixedCosts = $this->breakEvenData['total_fixed_costs'] ?? 0;
        $variableCostRatio = $this->breakEvenData['variable_cost_ratio'] ?? 0;

        $revenuePoints = [];
        $totalCostPoints = [];
        $fixedCostPoints = [];
        $labels = [];

        for ($i = 0; $i <= $breakEvenRevenue * 2; $i += $breakEvenRevenue / 10) {
            $labels[] = number_format($i, 0);
            $revenuePoints[] = $i;
            $totalCostPoints[] = $fixedCosts + ($i * $variableCostRatio);
            $fixedCostPoints[] = $fixedCosts;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenuePoints,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
                [
                    'label' => 'Total Costs',
                    'data' => $totalCostPoints,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
                [
                    'label' => 'Fixed Costs',
                    'data' => $fixedCostPoints,
                    'borderColor' => 'rgb(156, 163, 175)',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'borderDash' => [5, 5],
                ],
            ],
        ];
    }

    private function getScenarioChartData(): array
    {
        $baseBreakEven = $this->breakEvenData['break_even_revenue'] ?? 0;
        $scenarioBreakEven = $this->scenarioAnalysis['adjusted_break_even_revenue'] ?? 0;

        return [
            'labels' => ['Base Scenario', 'Adjusted Scenario'],
            'datasets' => [
                [
                    'label' => 'Break-Even Revenue',
                    'data' => [$baseBreakEven, $scenarioBreakEven],
                    'backgroundColor' => ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                ],
            ],
        ];
    }

    #[Computed]
    public function products()
    {
        return Product::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.finance.break-even-analysis');
    }
}
