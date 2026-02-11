<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class ManageCostTrackingAction
{
    /**
     * Calculate comprehensive cost tracking metrics
     * Based on RestoPos Management Playbook cost management principles
     */
    public function __invoke(array $params = []): array
    {
        $startDate = $params['start_date'] ?? Carbon::now()->startOfMonth();
        $endDate = $params['end_date'] ?? Carbon::now()->endOfMonth();
        $includeProjections = $params['include_projections'] ?? true;

        return [
            'period' => [
                'start_date'     => $startDate->format('Y-m-d'),
                'end_date'       => $endDate->format('Y-m-d'),
                'days_in_period' => $startDate->diffInDays($endDate) + 1,
            ],
            'cogs_analysis'                   => $this->calculateCogsAnalysis($startDate, $endDate),
            'wastage_tracking'                => $this->calculateWastageMetrics($startDate, $endDate),
            'marketing_spend_analysis'        => $this->calculateMarketingSpend($startDate, $endDate),
            'cost_ratios'                     => $this->calculateCostRatios($startDate, $endDate),
            'cost_trends'                     => $this->analyzeCostTrends($startDate, $endDate),
            'cost_optimization_opportunities' => $this->identifyCostOptimizations($startDate, $endDate),
            'projections'                     => $includeProjections ? $this->generateCostProjections($startDate, $endDate) : null,
            'alerts'                          => $this->generateCostAlerts($startDate, $endDate),
            'recommendations'                 => $this->generateCostRecommendations($startDate, $endDate),
        ];
    }

    /**
     * Calculate Cost of Goods Sold (COGS) analysis
     * Target: 25-35% of revenue per RestoPos playbook
     */
    private function calculateCogsAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        // Get completed sales for the period
        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['items.menuItem'])
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $totalCogs = $sales->sum(function ($sale) {
            return $sale->items->sum(function ($item) {
                return ($item->menuItem->cost_price ?? 0) * $item->quantity;
            });
        });

        // Calculate COGS by category
        $cogsByCategory = $sales->flatMap(function ($sale) {
            return $sale->items->map(function ($item) {
                return [
                    'category' => $item->menuItem->category ?? 'uncategorized',
                    'cost'     => ($item->menuItem->cost_price ?? 0) * $item->quantity,
                    'revenue'  => $item->price * $item->quantity,
                ];
            });
        })->groupBy('category')->map(function ($items, $category) {
            return [
                'category'        => $category,
                'total_cost'      => $items->sum('cost'),
                'total_revenue'   => $items->sum('revenue'),
                'cost_percentage' => $items->sum('revenue') > 0
                    ? ($items->sum('cost') / $items->sum('revenue')) * 100
                    : 0,
            ];
        })->values();

        $cogsPercentage = $totalRevenue > 0 ? ($totalCogs / $totalRevenue) * 100 : 0;

        return [
            'total_cogs'           => $totalCogs,
            'total_revenue'        => $totalRevenue,
            'cogs_percentage'      => $cogsPercentage,
            'target_range'         => ['min' => 25, 'max' => 35],
            'status'               => $this->getCostStatus($cogsPercentage, 25, 35, 'lower_better'),
            'variance_from_target' => $cogsPercentage - 30, // 30% is middle of target range
            'cogs_by_category'     => $cogsByCategory,
            'daily_average_cogs'   => $totalCogs / max(1, $startDate->diffInDays($endDate) + 1),
        ];
    }

    /**
     * Calculate wastage metrics
     * Track food waste and its impact on profitability
     */
    private function calculateWastageMetrics(Carbon $startDate, Carbon $endDate): array
    {
        // Get wastage expenses (assuming expense category 'wastage' or 'food_waste')
        $wastageExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['wastage', 'food_waste', 'spoilage'])
            ->get();

        $totalWastage = $wastageExpenses->sum('amount');
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        $wastagePercentage = $totalRevenue > 0 ? ($totalWastage / $totalRevenue) * 100 : 0;

        // Wastage by category/reason
        $wastageByReason = $wastageExpenses->groupBy('subcategory')->map(function ($expenses, $reason) {
            return [
                'reason' => $reason ?: 'unspecified',
                'amount' => $expenses->sum('amount'),
                'count'  => $expenses->count(),
            ];
        })->values();

        return [
            'total_wastage'                 => $totalWastage,
            'wastage_percentage_of_revenue' => $wastagePercentage,
            'target_percentage'             => 2.0, // Target: <2% of revenue
            'status'                        => $wastagePercentage <= 2.0 ? 'good' : ($wastagePercentage <= 4.0 ? 'warning' : 'critical'),
            'wastage_by_reason'             => $wastageByReason,
            'daily_average_wastage'         => $totalWastage / max(1, $startDate->diffInDays($endDate) + 1),
            'potential_profit_impact'       => $totalWastage * 0.7, // Assuming 70% of wastage could have been profit
        ];
    }

    /**
     * Calculate marketing spend analysis
     * Track marketing ROI and customer acquisition costs
     */
    private function calculateMarketingSpend(Carbon $startDate, Carbon $endDate): array
    {
        // Get marketing expenses
        $marketingExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['marketing', 'advertising', 'promotions', 'social_media', 'ads'])
            ->get();

        $totalMarketingSpend = $marketingExpenses->sum('amount');
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        $marketingPercentage = $totalRevenue > 0 ? ($totalMarketingSpend / $totalRevenue) * 100 : 0;

        // Marketing spend by channel
        $marketingByChannel = $marketingExpenses->groupBy('subcategory')->map(function ($expenses, $channel) use ($totalMarketingSpend) {
            return [
                'channel'                 => $channel ?: 'general',
                'amount'                  => $expenses->sum('amount'),
                'percentage_of_marketing' => $totalMarketingSpend > 0
                    ? ($expenses->sum('amount') / $totalMarketingSpend) * 100
                    : 0,
            ];
        })->values();

        // Calculate estimated ROI (simplified)
        $newCustomers = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('customer', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->distinct('customer_id')
            ->count();

        $customerAcquisitionCost = $newCustomers > 0 ? $totalMarketingSpend / $newCustomers : 0;

        return [
            'total_marketing_spend'           => $totalMarketingSpend,
            'marketing_percentage_of_revenue' => $marketingPercentage,
            'target_percentage'               => 5.0, // Target: ~5% of revenue
            'status'                          => $marketingPercentage <= 5.0 ? 'good' : ($marketingPercentage <= 8.0 ? 'warning' : 'critical'),
            'marketing_by_channel'            => $marketingByChannel,
            'roi_metrics'                     => [
                'new_customers_acquired'       => $newCustomers,
                'customer_acquisition_cost'    => $customerAcquisitionCost,
                'revenue_per_marketing_dollar' => $totalMarketingSpend > 0 ? $totalRevenue / $totalMarketingSpend : 0,
                'estimated_roi_percentage'     => $totalMarketingSpend > 0 ? (($totalRevenue - $totalMarketingSpend) / $totalMarketingSpend) * 100 : 0,
            ],
            'daily_average_marketing_spend' => $totalMarketingSpend / max(1, $startDate->diffInDays($endDate) + 1),
        ];
    }

    /** Calculate overall cost ratios and efficiency metrics */
    private function calculateCostRatios(Carbon $startDate, Carbon $endDate): array
    {
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return [
            'total_revenue'         => $totalRevenue,
            'total_expenses'        => $totalExpenses,
            'expense_ratio'         => $totalRevenue > 0 ? ($totalExpenses / $totalRevenue) * 100 : 0,
            'profit_margin'         => $totalRevenue > 0 ? (($totalRevenue - $totalExpenses) / $totalRevenue) * 100 : 0,
            'cost_efficiency_score' => $this->calculateCostEfficiencyScore($startDate, $endDate),
        ];
    }

    /** Analyze cost trends over time */
    private function analyzeCostTrends(Carbon $startDate, Carbon $endDate): array
    {
        $previousPeriodStart = $startDate->copy()->subDays($startDate->diffInDays($endDate) + 1);
        $previousPeriodEnd = $startDate->copy()->subDay();

        $currentCosts = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $previousCosts = Expense::whereBetween('date', [$previousPeriodStart, $previousPeriodEnd])->sum('amount');

        $costChange = $previousCosts > 0 ? (($currentCosts - $previousCosts) / $previousCosts) * 100 : 0;

        return [
            'current_period_costs'   => $currentCosts,
            'previous_period_costs'  => $previousCosts,
            'cost_change_percentage' => $costChange,
            'trend_direction'        => $costChange > 5 ? 'increasing' : ($costChange < -5 ? 'decreasing' : 'stable'),
        ];
    }

    /** Identify cost optimization opportunities */
    private function identifyCostOptimizations(Carbon $startDate, Carbon $endDate): array
    {
        $opportunities = [];

        // High COGS opportunity
        $cogsAnalysis = $this->calculateCogsAnalysis($startDate, $endDate);

        if ($cogsAnalysis['cogs_percentage'] > 35) {
            $opportunities[] = [
                'type'              => 'cogs_reduction',
                'priority'          => 'high',
                'description'       => 'COGS is above target range (35%). Consider supplier negotiation or menu optimization.',
                'potential_savings' => ($cogsAnalysis['cogs_percentage'] - 30) / 100 * $cogsAnalysis['total_revenue'],
            ];
        }

        // High wastage opportunity
        $wastageAnalysis = $this->calculateWastageMetrics($startDate, $endDate);

        if ($wastageAnalysis['wastage_percentage_of_revenue'] > 2) {
            $opportunities[] = [
                'type'              => 'wastage_reduction',
                'priority'          => 'medium',
                'description'       => 'Food wastage exceeds 2% of revenue. Implement better inventory management.',
                'potential_savings' => $wastageAnalysis['total_wastage'] * 0.5, // Assume 50% reduction possible
            ];
        }

        return $opportunities;
    }

    /** Generate cost projections for next period */
    private function generateCostProjections(Carbon $startDate, Carbon $endDate): array
    {
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        $dailyAverageCosts = Expense::whereBetween('date', [$startDate, $endDate])
            ->sum('amount') / $daysInPeriod;

        $nextPeriodDays = 30; // Project for next 30 days
        $projectedCosts = $dailyAverageCosts * $nextPeriodDays;

        return [
            'projection_period_days' => $nextPeriodDays,
            'daily_average_costs'    => $dailyAverageCosts,
            'projected_total_costs'  => $projectedCosts,
            'confidence_level'       => 'medium', // Based on historical data availability
        ];
    }

    /** Generate cost-related alerts */
    private function generateCostAlerts(Carbon $startDate, Carbon $endDate): array
    {
        $alerts = [];

        $cogsAnalysis = $this->calculateCogsAnalysis($startDate, $endDate);

        if ($cogsAnalysis['cogs_percentage'] > 40) {
            $alerts[] = [
                'level'   => 'critical',
                'type'    => 'cogs',
                'message' => 'COGS exceeds 40% - immediate action required',
                'action'  => 'Review supplier contracts and menu pricing',
            ];
        }

        $wastageAnalysis = $this->calculateWastageMetrics($startDate, $endDate);

        if ($wastageAnalysis['wastage_percentage_of_revenue'] > 5) {
            $alerts[] = [
                'level'   => 'warning',
                'type'    => 'wastage',
                'message' => 'Food wastage exceeds 5% of revenue',
                'action'  => 'Implement inventory management improvements',
            ];
        }

        return $alerts;
    }

    /** Generate cost management recommendations */
    private function generateCostRecommendations(Carbon $startDate, Carbon $endDate): array
    {
        $recommendations = [];

        $cogsAnalysis = $this->calculateCogsAnalysis($startDate, $endDate);

        if ($cogsAnalysis['cogs_percentage'] > 35) {
            $recommendations[] = 'Negotiate better supplier terms or find alternative suppliers';
            $recommendations[] = 'Review menu pricing to improve margins';
            $recommendations[] = 'Optimize portion sizes to reduce food costs';
        }

        $wastageAnalysis = $this->calculateWastageMetrics($startDate, $endDate);

        if ($wastageAnalysis['wastage_percentage_of_revenue'] > 2) {
            $recommendations[] = 'Implement first-in-first-out (FIFO) inventory system';
            $recommendations[] = 'Train staff on proper food storage and handling';
            $recommendations[] = 'Review menu planning to reduce overproduction';
        }

        return $recommendations;
    }

    /** Calculate cost efficiency score (0-100) */
    private function calculateCostEfficiencyScore(Carbon $startDate, Carbon $endDate): float
    {
        $cogsAnalysis = $this->calculateCogsAnalysis($startDate, $endDate);
        $wastageAnalysis = $this->calculateWastageMetrics($startDate, $endDate);

        // Score each component (0-100)
        $cogsScore = $this->getComponentScore($cogsAnalysis['cogs_percentage'], 30, 25, 35);
        $wastageScore = $this->getComponentScore($wastageAnalysis['wastage_percentage_of_revenue'], 1, 0, 2);

        // Weighted average (COGS 40%, Wastage 20%)
        return ($cogsScore * 0.4) + ($wastageScore * 0.2);
    }

    /** Get component score for efficiency calculation */
    private function getComponentScore(float $actual, float $target, float $excellent, float $poor): float
    {
        if ($actual <= $excellent) {
            return 100;
        }

        if ($actual >= $poor) {
            return 0;
        }

        // Linear interpolation between excellent and poor
        return 100 - (($actual - $excellent) / ($poor - $excellent)) * 100;
    }

    /** Get cost status based on target ranges */
    private function getCostStatus(float $actual, float $min, float $max, string $direction = 'lower_better'): string
    {
        if ($direction === 'lower_better') {
            if ($actual <= $min) {
                return 'excellent';
            }

            if ($actual <= $max) {
                return 'good';
            }

            if ($actual <= $max * 1.2) {
                return 'warning';
            }

            return 'critical';
        } else {
            if ($actual >= $max) {
                return 'excellent';
            }

            if ($actual >= $min) {
                return 'good';
            }

            if ($actual >= $min * 0.8) {
                return 'warning';
            }

            return 'critical';
        }
    }
}
