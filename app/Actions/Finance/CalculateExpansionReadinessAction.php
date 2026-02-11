<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalculateExpansionReadinessAction
{
    public function __invoke(array $params = []): array
    {
        $period = $params['period'] ?? 'monthly';
        $startDate = $this->getStartDate($period);
        $endDate = now();

        return [
            'readiness_score'           => $this->calculateReadinessScore($startDate, $endDate),
            'utilization_metrics'       => $this->getUtilizationMetrics($startDate, $endDate),
            'profitability_metrics'     => $this->getProfitabilityMetrics($startDate, $endDate),
            'financial_health'          => $this->getFinancialHealth($startDate, $endDate),
            'operational_metrics'       => $this->getOperationalMetrics($startDate, $endDate),
            'expansion_recommendations' => $this->getExpansionRecommendations($startDate, $endDate),
            'risk_factors'              => $this->getRiskFactors($startDate, $endDate),
            'investment_requirements'   => $this->getInvestmentRequirements(),
            'timeline_projections'      => $this->getTimelineProjections($startDate, $endDate),
            'market_analysis'           => $this->getMarketAnalysis($startDate, $endDate),
        ];
    }

    private function calculateReadinessScore(Carbon $startDate, Carbon $endDate): array
    {
        $utilizationScore = $this->getUtilizationScore($startDate, $endDate);
        $profitabilityScore = $this->getProfitabilityScore($startDate, $endDate);
        $financialHealthScore = $this->getFinancialHealthScore($startDate, $endDate);
        $operationalScore = $this->getOperationalScore($startDate, $endDate);
        $marketScore = $this->getMarketScore($startDate, $endDate);

        $totalScore = (
            $utilizationScore * 0.25 +
            $profitabilityScore * 0.30 +
            $financialHealthScore * 0.25 +
            $operationalScore * 0.15 +
            $marketScore * 0.05
        );

        return [
            'total_score'      => round($totalScore, 1),
            'readiness_level'  => $this->getReadinessLevel($totalScore),
            'component_scores' => [
                'utilization'      => $utilizationScore,
                'profitability'    => $profitabilityScore,
                'financial_health' => $financialHealthScore,
                'operational'      => $operationalScore,
                'market'           => $marketScore,
            ],
            'recommendation' => $this->getOverallRecommendation($totalScore),
        ];
    }

    private function getUtilizationMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $totalTables = Table::count();
        $totalCapacity = Table::sum('capacity');

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalSales = $sales->count();
        $peakHours = $this->getPeakHours($sales);
        $averageSaleValue = $sales->avg('total_amount') ?? 0;
        $tableUtilization = $this->calculateTableUtilization($sales, $totalTables, $startDate, $endDate);
        $capacityUtilization = $this->calculateCapacityUtilization($sales, $totalCapacity, $startDate, $endDate);

        return [
            'table_utilization_rate'    => round($tableUtilization, 2),
            'capacity_utilization_rate' => round($capacityUtilization, 2),
            'peak_hours_utilization'    => $peakHours,
            'average_sales_per_day'     => round($totalSales / max(1, $startDate->diffInDays($endDate)), 2),
            'total_sales'               => $totalSales,
            'average_sale_value'        => round($averageSaleValue, 2),
            'utilization_trend'         => $this->getUtilizationTrend($startDate, $endDate),
            'bottlenecks'               => $this->identifyBottlenecks($sales, $totalTables, $totalCapacity),
        ];
    }

    private function getProfitabilityMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $revenue = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $profit = $revenue - $expenses;
        $profitMargin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

        $cogs = $this->calculateCOGS($startDate, $endDate);
        $grossProfit = $revenue - $cogs;
        $grossMargin = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;

        $monthlyRevenue = $this->getMonthlyRevenue($startDate, $endDate);
        $revenueGrowth = $this->calculateRevenueGrowth($monthlyRevenue);

        return [
            'total_revenue'       => round($revenue, 2),
            'total_expenses'      => round($expenses, 2),
            'net_profit'          => round($profit, 2),
            'profit_margin'       => round($profitMargin, 2),
            'gross_profit'        => round($grossProfit, 2),
            'gross_margin'        => round($grossMargin, 2),
            'revenue_growth_rate' => round($revenueGrowth, 2),
            'break_even_point'    => $this->calculateBreakEvenPoint($expenses, $grossMargin),
            'profitability_trend' => $this->getProfitabilityTrend($startDate, $endDate),
            'roi_projection'      => $this->calculateROIProjection($profit, $revenue),
        ];
    }

    private function getFinancialHealth(Carbon $startDate, Carbon $endDate): array
    {
        $currentCash = $this->getCurrentCashPosition();
        $monthlyExpenses = $this->getAverageMonthlyExpenses($startDate, $endDate);
        $cashRunway = $monthlyExpenses > 0 ? $currentCash / $monthlyExpenses : 0;

        $debtToEquity = $this->calculateDebtToEquityRatio();
        $currentRatio = $this->calculateCurrentRatio();
        $quickRatio = $this->calculateQuickRatio();

        return [
            'cash_position'             => round($currentCash, 2),
            'cash_runway_months'        => round($cashRunway, 1),
            'debt_to_equity_ratio'      => round($debtToEquity, 2),
            'current_ratio'             => round($currentRatio, 2),
            'quick_ratio'               => round($quickRatio, 2),
            'working_capital'           => $this->calculateWorkingCapital(),
            'financial_stability_score' => $this->calculateFinancialStabilityScore($cashRunway, $debtToEquity, $currentRatio),
            'credit_worthiness'         => $this->assessCreditWorthiness($cashRunway, $debtToEquity),
        ];
    }

    private function getOperationalMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $averageServiceTime = $this->calculateAverageServiceTime($sales);
        $customerSatisfaction = $this->getCustomerSatisfactionScore($sales);
        $inventoryTurnover = $this->calculateInventoryTurnover($startDate, $endDate);

        return [
            'average_service_time'         => round($averageServiceTime, 2),
            'customer_satisfaction_score'  => round($customerSatisfaction, 2),
            'inventory_turnover_rate'      => round($inventoryTurnover, 2),
            'operational_efficiency_score' => $this->calculateOperationalEfficiencyScore($averageServiceTime, $customerSatisfaction),
            'scalability_readiness'        => $this->assessScalabilityReadiness($sales, $startDate, $endDate),
        ];
    }

    private function getExpansionRecommendations(Carbon $startDate, Carbon $endDate): array
    {
        $readinessScore = $this->calculateReadinessScore($startDate, $endDate);
        $utilizationMetrics = $this->getUtilizationMetrics($startDate, $endDate);
        $profitabilityMetrics = $this->getProfitabilityMetrics($startDate, $endDate);

        $recommendations = [];

        // High utilization recommendations
        if ($utilizationMetrics['table_utilization_rate'] > 85) {
            $recommendations[] = [
                'type'        => 'expansion_ready',
                'priority'    => 'high',
                'title'       => 'High Table Utilization Detected',
                'description' => 'Your table utilization is above 85%, indicating strong demand for expansion.',
                'action'      => 'Consider expanding seating capacity or opening a second location.',
            ];
        }

        // Profitability recommendations
        if ($profitabilityMetrics['profit_margin'] > 15) {
            $recommendations[] = [
                'type'        => 'financial_ready',
                'priority'    => 'high',
                'title'       => 'Strong Profit Margins',
                'description' => 'Your profit margin of '.$profitabilityMetrics['profit_margin'].'% indicates financial readiness for expansion.',
                'action'      => 'Leverage strong profitability to fund expansion initiatives.',
            ];
        }

        // Growth recommendations
        if ($profitabilityMetrics['revenue_growth_rate'] > 20) {
            $recommendations[] = [
                'type'        => 'growth_momentum',
                'priority'    => 'medium',
                'title'       => 'Strong Revenue Growth',
                'description' => 'Revenue growth of '.$profitabilityMetrics['revenue_growth_rate'].'% shows positive market momentum.',
                'action'      => 'Capitalize on growth momentum with strategic expansion.',
            ];
        }

        // Risk mitigation recommendations
        if ($readinessScore['total_score'] < 70) {
            $recommendations[] = [
                'type'        => 'improvement_needed',
                'priority'    => 'high',
                'title'       => 'Expansion Readiness Below Threshold',
                'description' => 'Current readiness score of '.$readinessScore['total_score'].' indicates areas for improvement.',
                'action'      => 'Focus on improving weak areas before considering expansion.',
            ];
        }

        return $recommendations;
    }

    private function getRiskFactors(Carbon $startDate, Carbon $endDate): array
    {
        $risks = [];

        $cashRunway = $this->getCurrentCashPosition() / max(1, $this->getAverageMonthlyExpenses($startDate, $endDate));

        if ($cashRunway < 6) {
            $risks[] = [
                'type'        => 'financial',
                'severity'    => 'high',
                'title'       => 'Low Cash Runway',
                'description' => 'Cash runway of '.round($cashRunway, 1).' months is below recommended 6+ months.',
                'mitigation'  => 'Secure additional funding or improve cash flow before expansion.',
            ];
        }

        $profitMargin = $this->getProfitabilityMetrics($startDate, $endDate)['profit_margin'];

        if ($profitMargin < 10) {
            $risks[] = [
                'type'        => 'profitability',
                'severity'    => 'medium',
                'title'       => 'Low Profit Margins',
                'description' => 'Profit margin of '.round($profitMargin, 1).'% may not support expansion costs.',
                'mitigation'  => 'Improve operational efficiency and cost management.',
            ];
        }

        $utilizationRate = $this->getUtilizationMetrics($startDate, $endDate)['table_utilization_rate'];

        if ($utilizationRate < 60) {
            $risks[] = [
                'type'        => 'operational',
                'severity'    => 'medium',
                'title'       => 'Low Utilization Rate',
                'description' => 'Table utilization of '.round($utilizationRate, 1).'% indicates underutilized capacity.',
                'mitigation'  => 'Focus on marketing and customer acquisition before expansion.',
            ];
        }

        return $risks;
    }

    private function getInvestmentRequirements(): array
    {
        return [
            'initial_investment' => [
                'equipment'       => 150000,
                'renovation'      => 100000,
                'inventory'       => 25000,
                'marketing'       => 15000,
                'working_capital' => 50000,
                'total'           => 340000,
            ],
            'ongoing_costs' => [
                'rent'          => 8000,
                'utilities'     => 2000,
                'insurance'     => 1500,
                'total_monthly' => 26500,
            ],
            'break_even_timeline' => '12-18 months',
            'roi_projection'      => '18-24 months',
        ];
    }

    private function getTimelineProjections(Carbon $startDate, Carbon $endDate): array
    {
        $currentMetrics = $this->getProfitabilityMetrics($startDate, $endDate);

        return [
            'preparation_phase' => [
                'duration'       => '3-6 months',
                'activities'     => ['Site selection', 'Permits and licensing', 'Design and planning', 'Financing'],
                'estimated_cost' => 25000,
            ],
            'construction_phase' => [
                'duration'       => '2-4 months',
                'activities'     => ['Renovation', 'Equipment installation', 'Staff hiring', 'Training'],
                'estimated_cost' => 275000,
            ],
            'launch_phase' => [
                'duration'       => '1-2 months',
                'activities'     => ['Soft opening', 'Marketing campaign', 'Operations optimization'],
                'estimated_cost' => 40000,
            ],
            'stabilization_phase' => [
                'duration'          => '6-12 months',
                'activities'        => ['Customer base building', 'Process refinement', 'Profitability achievement'],
                'projected_revenue' => $currentMetrics['total_revenue'] * 0.7, // Conservative estimate
            ],
        ];
    }

    private function getMarketAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        $revenueGrowth = $this->getProfitabilityMetrics($startDate, $endDate)['revenue_growth_rate'];
        $customerGrowth = $this->calculateCustomerGrowth($startDate, $endDate);

        return [
            'market_demand'          => $this->assessMarketDemand($revenueGrowth, $customerGrowth),
            'competition_analysis'   => $this->getCompetitionAnalysis(),
            'location_opportunities' => $this->getLocationOpportunities(),
            'customer_demographics'  => $this->getCustomerDemographics($startDate, $endDate),
            'market_saturation'      => $this->assessMarketSaturation(),
        ];
    }

    // Helper methods
    private function getStartDate(string $period): Carbon
    {
        return match ($period) {
            'weekly'    => now()->subWeeks(12),
            'monthly'   => now()->subMonths(12),
            'quarterly' => now()->subMonths(24),
            default     => now()->subMonths(6)
        };
    }

    private function getUtilizationScore(Carbon $startDate, Carbon $endDate): float
    {
        $metrics = $this->getUtilizationMetrics($startDate, $endDate);
        $utilizationRate = $metrics['table_utilization_rate'];

        return min(100, ($utilizationRate / 85) * 100); // 85% is optimal utilization
    }

    private function getProfitabilityScore(Carbon $startDate, Carbon $endDate): float
    {
        $metrics = $this->getProfitabilityMetrics($startDate, $endDate);
        $profitMargin = $metrics['profit_margin'];

        return min(100, ($profitMargin / 20) * 100); // 20% is excellent profit margin
    }

    private function getFinancialHealthScore(Carbon $startDate, Carbon $endDate): float
    {
        $health = $this->getFinancialHealth($startDate, $endDate);
        $cashRunway = $health['cash_runway_months'];

        return min(100, ($cashRunway / 12) * 100); // 12 months is ideal cash runway
    }

    private function getOperationalScore(Carbon $startDate, Carbon $endDate): float
    {
        $metrics = $this->getOperationalMetrics($startDate, $endDate);

        return $metrics['operational_efficiency_score'];
    }

    private function getMarketScore(Carbon $startDate, Carbon $endDate): float
    {
        $analysis = $this->getMarketAnalysis($startDate, $endDate);

        return $analysis['market_demand']['score'] ?? 75; // Default moderate score
    }

    private function getReadinessLevel(float $score): string
    {
        return match (true) {
            $score >= 85 => 'excellent',
            $score >= 70 => 'good',
            $score >= 55 => 'fair',
            default      => 'poor'
        };
    }

    private function getOverallRecommendation(float $score): string
    {
        return match (true) {
            $score >= 85 => 'Highly recommended for expansion. All metrics indicate strong readiness.',
            $score >= 70 => 'Recommended for expansion with minor improvements in weak areas.',
            $score >= 55 => 'Consider expansion after addressing key improvement areas.',
            default      => 'Not recommended for expansion. Focus on improving current operations first.'
        };
    }

    // Placeholder methods for complex calculations
    private function calculateTableUtilization($sales, $totalTables, $startDate, $endDate): float
    {
        $days = max(1, $startDate->diffInDays($endDate));
        $averageSalesPerDay = $sales->count() / $days;
        $averageTablesUsedPerDay = min($totalTables, $averageSalesPerDay / 3); // Assuming 3 sales per table per day

        return ($averageTablesUsedPerDay / max(1, $totalTables)) * 100;
    }

    private function calculateCapacityUtilization($sales, $totalCapacity, $startDate, $endDate): float
    {
        $totalCustomers = $sales->sum('customer_count') ?: $sales->count() * 2; // Default 2 customers per sale
        $days = max(1, $startDate->diffInDays($endDate));
        $averageCustomersPerDay = $totalCustomers / $days;

        return ($averageCustomersPerDay / max(1, $totalCapacity)) * 100;
    }

    private function getPeakHours($sales): array
    {
        $hourlySales = $sales->groupBy(function ($sale) {
            return $sale->created_at->format('H');
        })->map->count()->sortDesc();

        return [
            'peak_hour'               => $hourlySales->keys()->first() ?? '12',
            'peak_sales'              => $hourlySales->first() ?? 0,
            'utilization_during_peak' => min(100, ($hourlySales->first() ?? 0) / 10 * 100),
        ];
    }

    private function getUtilizationTrend($startDate, $endDate): string
    {
        // Simplified trend calculation
        $firstHalf = Sale::whereBetween('created_at', [$startDate, $startDate->copy()->addDays($startDate->diffInDays($endDate) / 2)])->count();
        $secondHalf = Sale::whereBetween('created_at', [$startDate->copy()->addDays($startDate->diffInDays($endDate) / 2), $endDate])->count();

        if ($secondHalf > $firstHalf * 1.1) {
            return 'increasing';
        }

        if ($secondHalf < $firstHalf * 0.9) {
            return 'decreasing';
        }

        return 'stable';
    }

    private function identifyBottlenecks($sales, $totalTables, $totalCapacity): array
    {
        $bottlenecks = [];

        $peakHourSales = $this->getPeakHours($sales)['peak_sales'];

        if ($peakHourSales > $totalTables * 0.9) {
            $bottlenecks[] = 'Table capacity during peak hours';
        }

        return $bottlenecks;
    }

    private function calculateCOGS($startDate, $endDate): float
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['food_cost', 'beverage_cost'])
            ->sum('amount');
    }

    private function getMonthlyRevenue($startDate, $endDate): Collection
    {
        return Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('year', 'month')
            ->saleBy('year')
            ->saleBy('month')
            ->get();
    }

    private function calculateRevenueGrowth($monthlyRevenue): float
    {
        if ($monthlyRevenue->count() < 2) {
            return 0;
        }

        $first = $monthlyRevenue->first()->revenue;
        $last = $monthlyRevenue->last()->revenue;

        return $first > 0 ? (($last - $first) / $first) * 100 : 0;
    }

    private function calculateBreakEvenPoint($expenses, $grossMargin): array
    {
        $fixedCosts = $expenses * 0.6; // Assume 60% are fixed costs
        $breakEvenRevenue = $grossMargin > 0 ? $fixedCosts / ($grossMargin / 100) : 0;

        return [
            'revenue_required'   => round($breakEvenRevenue, 2),
            'days_to_break_even' => $breakEvenRevenue > 0 ? round($breakEvenRevenue / ($expenses / 30), 0) : 0,
        ];
    }

    private function getProfitabilityTrend($startDate, $endDate): string
    {
        $monthlyData = $this->getMonthlyRevenue($startDate, $endDate);

        if ($monthlyData->count() < 2) {
            return 'insufficient_data';
        }

        $trend = $monthlyData->last()->revenue > $monthlyData->first()->revenue ? 'improving' : 'declining';

        return $trend;
    }

    private function calculateROIProjection($profit, $revenue): array
    {
        $investmentRequired = 340000; // From getInvestmentRequirements
        $annualProfit = $profit * 12; // Assuming monthly profit
        $roi = $annualProfit > 0 ? ($annualProfit / $investmentRequired) * 100 : 0;

        return [
            'projected_annual_roi' => round($roi, 2),
            'payback_period_years' => $roi > 0 ? round(100 / $roi, 1) : 0,
        ];
    }

    private function getCurrentCashPosition(): float
    {
        // This would typically come from a cash register or accounting system
        return 150000; // Placeholder value
    }

    private function getAverageMonthlyExpenses($startDate, $endDate): float
    {
        $months = max(1, $startDate->diffInMonths($endDate));
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');

        return $totalExpenses / $months;
    }

    private function calculateDebtToEquityRatio(): float
    {
        // Placeholder calculation
        return 0.3; // 30% debt to equity ratio
    }

    private function calculateCurrentRatio(): float
    {
        // Placeholder calculation
        return 1.5; // Current assets / Current liabilities
    }

    private function calculateQuickRatio(): float
    {
        // Placeholder calculation
        return 1.2; // (Current assets - Inventory) / Current liabilities
    }

    private function calculateWorkingCapital(): float
    {
        // Placeholder calculation
        return 75000; // Current assets - Current liabilities
    }

    private function calculateFinancialStabilityScore($cashRunway, $debtToEquity, $currentRatio): float
    {
        $cashScore = min(100, ($cashRunway / 12) * 100);
        $debtScore = max(0, 100 - ($debtToEquity * 100));
        $liquidityScore = min(100, ($currentRatio / 2) * 100);

        return ($cashScore + $debtScore + $liquidityScore) / 3;
    }

    private function assessCreditWorthiness($cashRunway, $debtToEquity): string
    {
        if ($cashRunway >= 12 && $debtToEquity <= 0.3) {
            return 'excellent';
        }

        if ($cashRunway >= 6 && $debtToEquity <= 0.5) {
            return 'good';
        }

        if ($cashRunway >= 3 && $debtToEquity <= 0.7) {
            return 'fair';
        }

        return 'poor';
    }

    private function calculateAverageServiceTime($sales): float
    {
        // Placeholder - would need actual service time tracking
        return 25.5; // minutes
    }

    private function getCustomerSatisfactionScore($sales): float
    {
        // Placeholder - would need actual customer feedback system
        return 4.2; // out of 5
    }

    private function calculateInventoryTurnover($startDate, $endDate): float
    {
        // Placeholder calculation
        return 12; // times per year
    }

    private function calculateOperationalEfficiencyScore($serviceTime, $satisfaction): float
    {
        $serviceScore = max(0, 100 - (($serviceTime - 20) * 2)); // Penalty for service time > 20 min
        $satisfactionScore = ($satisfaction / 5) * 100;

        return ($serviceScore + $satisfactionScore) / 3;
    }

    private function assessScalabilityReadiness($sales, $startDate, $endDate): array
    {
        $consistency = $this->calculateSaleConsistency($sales, $startDate, $endDate);
        $growth = $this->getUtilizationTrend($startDate, $endDate);

        return [
            'consistency_score'  => $consistency,
            'growth_trend'       => $growth,
            'scalability_rating' => $consistency > 80 && $growth === 'increasing' ? 'high' : 'medium',
        ];
    }

    private function calculateSaleConsistency($sales, $startDate, $endDate): float
    {
        $dailySales = $sales->groupBy(function ($sale) {
            return $sale->created_at->format('Y-m-d');
        })->map->count();

        if ($dailySales->count() < 2) {
            return 0;
        }

        $average = $dailySales->avg();
        $variance = $dailySales->map(function ($count) use ($average) {
            return pow($count - $average, 2);
        })->avg();

        $standardDeviation = sqrt($variance);
        $coefficientOfVariation = $average > 0 ? ($standardDeviation / $average) * 100 : 100;

        return max(0, 100 - $coefficientOfVariation);
    }

    private function calculateCustomerGrowth($startDate, $endDate): float
    {
        // Placeholder - would need actual customer tracking
        return 15; // 15% growth
    }

    private function assessMarketDemand($revenueGrowth, $customerGrowth): array
    {
        $demandScore = ($revenueGrowth + $customerGrowth) / 2;

        return [
            'score' => min(100, max(0, $demandScore + 50)), // Normalize to 0-100
            'level' => $demandScore > 20 ? 'high' : ($demandScore > 10 ? 'medium' : 'low'),
            'trend' => $demandScore > 0 ? 'growing' : 'declining',
        ];
    }

    private function getCompetitionAnalysis(): array
    {
        return [
            'competitive_density'       => 'medium',
            'market_share_opportunity'  => 'good',
            'differentiation_potential' => 'high',
        ];
    }

    private function getLocationOpportunities(): array
    {
        return [
            'high_potential_areas' => ['Downtown', 'Shopping District', 'Business Park'],
            'demographic_match'    => 'excellent',
            'accessibility_score'  => 85,
        ];
    }

    private function getCustomerDemographics($startDate, $endDate): array
    {
        return [
            'primary_age_group' => '25-45',
            'income_level'      => 'middle_to_upper',
            'dining_frequency'  => 'regular',
            'loyalty_score'     => 72,
        ];
    }

    private function assessMarketSaturation(): array
    {
        return [
            'saturation_level'     => 'moderate',
            'growth_potential'     => 'good',
            'recommended_strategy' => 'differentiation',
        ];
    }
}
