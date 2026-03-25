<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Calculate Break-Even Point Analysis
 *
 * Formula: BreakEvenUnits = FixedCosts / (SellingPricePerUnit - VariableCostPerUnit)
 * Target: As low as possible according to RestoPos Management Playbook
 */
final class CalculateBreakEvenAction
{
    public function __invoke(array $fixedCosts, ?Carbon $analysisDate = null): array
    {
        $analysisDate ??= now();

        // Calculate average sale metrics from last 30 days
        $saleMetrics = $this->calculateSaleMetrics($analysisDate);

        // Calculate monthly fixed costs
        $monthlyFixedCosts = $this->calculateMonthlyFixedCosts($fixedCosts);

        // Calculate break-even point
        $breakEvenAnalysis = $this->calculateBreakEvenPoint(
            $monthlyFixedCosts['total'],
            $saleMetrics['average_sale_value'],
            $saleMetrics['average_variable_cost_per_sale']
        );

        // Calculate current performance vs break-even
        $currentPerformance = $this->analyzeCurrentPerformance(
            $saleMetrics,
            $breakEvenAnalysis,
            $analysisDate
        );

        return [
            'fixed_costs' => $monthlyFixedCosts,
            'sale_metrics' => $saleMetrics,
            'break_even_analysis' => $breakEvenAnalysis,
            'current_performance' => $currentPerformance,
            'recommendations' => $this->getRecommendations($breakEvenAnalysis, $currentPerformance),
            'analysis_date' => $analysisDate->toDateString(),
            'calculated_at' => now()->toISOString(),
        ];
    }

    private function calculateSaleMetrics(Carbon $analysisDate): array
    {
        $startDate = $analysisDate->copy()->subDays(30);
        $endDate = $analysisDate->copy();

        // Get completed sales from last 30 days
        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        if ($sales->isEmpty()) {
            return [
                'total_sales' => 0,
                'average_sale_value' => 0,
                'average_variable_cost_per_sale' => 0,
                'contribution_margin_per_sale' => 0,
                'daily_average_sales' => 0,
                'monthly_projected_sales' => 0,
            ];
        }

        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total_amount');
        $averageSaleValue = $totalRevenue / $totalSales;

        // Calculate average variable cost per sale (COGS)
        $totalVariableCosts = SaleDetails::whereIn('sale_id', $sales->pluck('id'))
            ->sum(DB::raw('quantity * (SELECT cost FROM products WHERE products.id = sale_details.product_id)'));

        $averageVariableCostPerSale = $totalVariableCosts / $totalSales;
        $contributionMarginPerSale = $averageSaleValue - $averageVariableCostPerSale;

        $dailyAverageSales = $totalSales / 30;
        $monthlyProjectedSales = $dailyAverageSales * 30;

        return [
            'total_sales' => $totalSales,
            'average_sale_value' => round($averageSaleValue, 2),
            'average_variable_cost_per_sale' => round($averageVariableCostPerSale, 2),
            'contribution_margin_per_sale' => round($contributionMarginPerSale, 2),
            'contribution_margin_percentage' => round(($contributionMarginPerSale / $averageSaleValue) * 100, 2),
            'daily_average_sales' => round($dailyAverageSales, 1),
            'monthly_projected_sales' => round($monthlyProjectedSales, 0),
        ];
    }

    private function calculateMonthlyFixedCosts(array $fixedCosts): array
    {
        $costCategories = [
            'rent' => $fixedCosts['rent'] ?? 0,
            'salaries' => $fixedCosts['salaries'] ?? 0,
            'insurance' => $fixedCosts['insurance'] ?? 0,
            'utilities_fixed' => $fixedCosts['utilities_fixed'] ?? 0,
            'equipment_lease' => $fixedCosts['equipment_lease'] ?? 0,
            'software_subscriptions' => $fixedCosts['software_subscriptions'] ?? 0,
            'marketing_fixed' => $fixedCosts['marketing_fixed'] ?? 0,
            'other_fixed' => $fixedCosts['other_fixed'] ?? 0,
        ];

        $totalFixedCosts = array_sum($costCategories);

        return [
            'breakdown' => $costCategories,
            'total' => round($totalFixedCosts, 2),
            'daily' => round($totalFixedCosts / 30, 2),
        ];
    }

    private function calculateBreakEvenPoint(float $fixedCosts, float $averageSaleValue, float $averageVariableCost): array
    {
        $contributionMargin = $averageSaleValue - $averageVariableCost;

        if ($contributionMargin <= 0) {
            return [
                'break_even_sales_monthly' => null,
                'break_even_sales_daily' => null,
                'break_even_revenue_monthly' => null,
                'break_even_revenue_daily' => null,
                'contribution_margin' => round($contributionMargin, 2),
                'status' => 'impossible',
                'message' => __('Break-even impossible: Variable costs exceed selling price'),
            ];
        }

        $breakEvenSalesMonthly = $fixedCosts / $contributionMargin;
        $breakEvenSalesDaily = $breakEvenSalesMonthly / 30;
        $breakEvenRevenueMonthly = $breakEvenSalesMonthly * $averageSaleValue;
        $breakEvenRevenueDaily = $breakEvenSalesDaily * $averageSaleValue;

        return [
            'break_even_sales_monthly' => round($breakEvenSalesMonthly, 0),
            'break_even_sales_daily' => round($breakEvenSalesDaily, 1),
            'break_even_revenue_monthly' => round($breakEvenRevenueMonthly, 2),
            'break_even_revenue_daily' => round($breakEvenRevenueDaily, 2),
            'contribution_margin' => round($contributionMargin, 2),
            'contribution_margin_percentage' => round(($contributionMargin / $averageSaleValue) * 100, 2),
            'status' => 'calculable',
        ];
    }

    private function analyzeCurrentPerformance(array $saleMetrics, array $breakEvenAnalysis, Carbon $analysisDate): array
    {
        if ($breakEvenAnalysis['status'] === 'impossible') {
            return [
                'status' => 'critical',
                'message' => __('Unable to break even with current pricing structure'),
            ];
        }

        $currentMonthlySales = $saleMetrics['monthly_projected_sales'];
        $breakEvenMonthlySales = $breakEvenAnalysis['break_even_sales_monthly'];

        $salesAboveBreakEven = $currentMonthlySales - $breakEvenMonthlySales;
        $breakEvenRatio = $currentMonthlySales / $breakEvenMonthlySales;

        $status = match (true) {
            $breakEvenRatio >= 2.0 => 'excellent',
            $breakEvenRatio >= 1.5 => 'good',
            $breakEvenRatio >= 1.1 => 'marginal',
            $breakEvenRatio >= 1.0 => 'break_even',
            default => 'below_break_even'
        };

        // Calculate safety margin
        $safetyMargin = (($currentMonthlySales - $breakEvenMonthlySales) / $currentMonthlySales) * 100;

        return [
            'current_monthly_sales' => $currentMonthlySales,
            'break_even_monthly_sales' => $breakEvenMonthlySales,
            'sales_above_break_even' => round($salesAboveBreakEven, 0),
            'break_even_ratio' => round($breakEvenRatio, 2),
            'safety_margin_percentage' => round($safetyMargin, 2),
            'status' => $status,
            'days_to_break_even' => $breakEvenAnalysis['break_even_sales_daily'] > 0
                ? round($breakEvenAnalysis['break_even_sales_monthly'] / $saleMetrics['daily_average_sales'], 1)
                : null,
        ];
    }

    private function getRecommendations(array $breakEvenAnalysis, array $currentPerformance): array
    {
        $recommendations = [];

        if ($breakEvenAnalysis['status'] === 'impossible') {
            $recommendations[] = __('Critical: Restructure pricing immediately - variable costs exceed revenue.');
            $recommendations[] = __('Review supplier contracts and negotiate better rates.');
            $recommendations[] = __('Consider menu engineering to improve margins.');

            return $recommendations;
        }

        switch ($currentPerformance['status']) {
            case 'below_break_even':
                $recommendations[] = __('Operating below break-even. Immediate action required.');
                $recommendations[] = __('Increase marketing efforts to boost sale volume.');
                $recommendations[] = __('Review and optimize operational efficiency.');

                break;

            case 'break_even':
                $recommendations[] = __('Currently at break-even point. Focus on growth strategies.');
                $recommendations[] = __('Small improvements in efficiency will significantly impact profitability.');

                break;

            case 'marginal':
                $recommendations[] = __('Marginally profitable. Build safety margin through cost optimization.');
                $recommendations[] = __('Focus on customer retention and average sale value increase.');

                break;

            case 'good':
                $recommendations[] = __('Good performance above break-even. Maintain current operations.');
                $recommendations[] = __('Consider strategic investments for growth.');

                break;

            case 'excellent':
                $recommendations[] = __('Excellent performance! Strong foundation for expansion.');
                $recommendations[] = __('Evaluate opportunities for scaling operations.');

                break;
        }

        // Additional recommendations based on contribution margin
        if ($breakEvenAnalysis['contribution_margin_percentage'] < 60) {
            $recommendations[] = __('Low contribution margin. Review pricing strategy and cost structure.');
        }

        return $recommendations;
    }
}
