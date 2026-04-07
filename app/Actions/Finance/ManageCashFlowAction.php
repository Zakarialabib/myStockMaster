<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use Carbon\Carbon;

/**
 * Cash Flow Management System
 *
 * Implements RestoPos Management Playbook requirements:
 * - Maintain 2-3 months of expenses as buffer
 * - Surplus allocation: 50% Reinvestment, 30% Reserve, 20% Profit
 * - Automated cash flow monitoring and alerts
 */
final class ManageCashFlowAction
{
    public function __invoke(
        float $currentCashBalance,
        array $monthlyExpenses,
        ?Carbon $analysisDate = null
    ): array {
        $analysisDate ??= now();

        // Calculate buffer requirements
        $bufferAnalysis = $this->analyzeBufferRequirements($currentCashBalance, $monthlyExpenses);

        // Get recent cash flow trends
        $cashFlowTrends = $this->analyzeCashFlowTrends($analysisDate);

        // Calculate projected cash position
        $projections = $this->projectCashPosition($currentCashBalance, $cashFlowTrends, $monthlyExpenses);

        // Determine surplus allocation if applicable
        $surplusAllocation = $this->calculateSurplusAllocation(
            $bufferAnalysis,
            $cashFlowTrends
        );

        // Generate management recommendations
        $recommendations = $this->generateCashFlowRecommendations(
            $bufferAnalysis,
            $cashFlowTrends
        );

        // Create alerts for critical situations
        $alerts = $this->generateCashFlowAlerts(
            $bufferAnalysis,
            $projections
        );

        return [
            'current_position' => [
                'cash_balance' => round($currentCashBalance, 2),
                'analysis_date' => $analysisDate->toDateString(),
                'buffer_status' => $bufferAnalysis['status'],
                'buffer_months' => $bufferAnalysis['buffer_months'],
            ],
            'buffer_analysis' => $bufferAnalysis,
            'cash_flow_trends' => $cashFlowTrends,
            'projections' => $projections,
            'surplus_allocation' => $surplusAllocation,
            'recommendations' => $recommendations,
            'alerts' => $alerts,
            'management_actions' => $this->suggestManagementActions($bufferAnalysis),
            'calculated_at' => now()->toISOString(),
        ];
    }

    private function analyzeBufferRequirements(float $currentBalance, array $monthlyExpenses): array
    {
        $totalMonthlyExpenses = array_sum($monthlyExpenses);

        $bufferRequirements = [
            'minimum' => $totalMonthlyExpenses * 2,    // 2 months
            'target' => $totalMonthlyExpenses * 2.5,   // 2.5 months
            'optimal' => $totalMonthlyExpenses * 3,    // 3 months
        ];

        $bufferMonths = $totalMonthlyExpenses > 0
            ? $currentBalance / $totalMonthlyExpenses
            : 0;

        $status = match (true) {
            $currentBalance >= $bufferRequirements['optimal'] => 'optimal',
            $currentBalance >= $bufferRequirements['target'] => 'adequate',
            $currentBalance >= $bufferRequirements['minimum'] => 'minimum',
            default => 'critical'
        };

        return [
            'monthly_expenses' => round($totalMonthlyExpenses, 2),
            'expense_breakdown' => array_map(fn ($expense): float => round($expense, 2), $monthlyExpenses),
            'buffer_requirements' => array_map(fn (float|int $req): float => round($req, 2), $bufferRequirements),
            'current_buffer' => round($currentBalance, 2),
            'buffer_months' => round($bufferMonths, 1),
            'status' => $status,
            'shortfall' => $currentBalance < $bufferRequirements['target']
                ? round($bufferRequirements['target'] - $currentBalance, 2)
                : 0,
            'excess' => $currentBalance > $bufferRequirements['optimal']
                ? round($currentBalance - $bufferRequirements['optimal'], 2)
                : 0,
        ];
    }

    private function analyzeCashFlowTrends(Carbon $analysisDate): array
    {
        $periods = [
            'last_7_days' => $analysisDate->copy()->subDays(7),
            'last_30_days' => $analysisDate->copy()->subDays(30),
            'last_90_days' => $analysisDate->copy()->subDays(90),
        ];

        $trends = [];

        foreach ($periods as $period => $startDate) {
            $revenue = Sale::query()->whereBetween('created_at', [$startDate, $analysisDate])
                ->whereIn('payment_status', ['paid', 'partially_paid'])
                ->sum('total_amount');

            $orderCount = Sale::query()->whereBetween('created_at', [$startDate, $analysisDate])
                ->where('status', 'completed')
                ->count();

            $days = $analysisDate->diffInDays($startDate);
            $dailyAverage = $days > 0 ? $revenue / $days : 0;

            $trends[$period] = [
                'total_revenue' => round($revenue, 2),
                'order_count' => $orderCount,
                'daily_average' => round($dailyAverage, 2),
                'days' => $days,
            ];
        }

        // Calculate trend direction
        $trendDirection = $this->calculateTrendDirection($trends);

        return [
            'periods' => $trends,
            'trend_direction' => $trendDirection,
            'monthly_projection' => round($trends['last_30_days']['daily_average'] * 30, 2),
            'growth_rate' => $this->calculateGrowthRate($trends),
        ];
    }

    private function calculateTrendDirection(array $trends): string
    {
        $recent = $trends['last_7_days']['daily_average'];
        $previous = $trends['last_30_days']['daily_average'];

        if ($previous == 0) {
            return 'unknown';
        }

        $changePercent = (($recent - $previous) / $previous) * 100;

        return match (true) {
            $changePercent > 10 => 'strong_growth',
            $changePercent > 5 => 'growth',
            $changePercent > -5 => 'stable',
            $changePercent > -10 => 'decline',
            default => 'strong_decline'
        };
    }

    private function calculateGrowthRate(array $trends): array
    {
        $current = $trends['last_30_days']['daily_average'];
        $previous = $trends['last_90_days']['daily_average'] / 3; // Average of 90 days

        $growthRate = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;

        return [
            'monthly_growth_rate' => round($growthRate, 2),
            'current_monthly_average' => round($current, 2),
            'previous_monthly_average' => round($previous, 2),
        ];
    }

    private function projectCashPosition(float $currentBalance, array $trends, array $monthlyExpenses): array
    {
        $monthlyRevenue = $trends['monthly_projection'];
        $monthlyExpenseTotal = array_sum($monthlyExpenses);
        $monthlyNetCashFlow = $monthlyRevenue - $monthlyExpenseTotal;

        $projections = [];
        $balance = $currentBalance;

        for ($month = 1; $month <= 6; $month++) {
            $balance += $monthlyNetCashFlow;

            $projections['month_' . $month] = [
                'projected_balance' => round($balance, 2),
                'net_cash_flow' => round($monthlyNetCashFlow, 2),
                'buffer_months' => $monthlyExpenseTotal > 0 ? round($balance / $monthlyExpenseTotal, 1) : 0,
                'status' => $this->getProjectedStatus($balance, $monthlyExpenseTotal),
            ];
        }

        return [
            'monthly_net_cash_flow' => round($monthlyNetCashFlow, 2),
            'monthly_revenue_projection' => round($monthlyRevenue, 2),
            'monthly_expense_total' => round($monthlyExpenseTotal, 2),
            'six_month_projections' => $projections,
            'cash_runway_months' => $monthlyNetCashFlow < 0
                ? round($currentBalance / abs($monthlyNetCashFlow), 1)
                : null,
        ];
    }

    private function getProjectedStatus(float $balance, float $monthlyExpenses): string
    {
        $bufferMonths = $monthlyExpenses > 0 ? $balance / $monthlyExpenses : 0;

        return match (true) {
            $bufferMonths >= 3 => 'optimal',
            $bufferMonths >= 2.5 => 'adequate',
            $bufferMonths >= 2 => 'minimum',
            $bufferMonths >= 1 => 'warning',
            default => 'critical'
        };
    }

    private function calculateSurplusAllocation(array $bufferAnalysis, array $trends): ?array
    {
        // Only allocate surplus if we have excess above optimal buffer
        if ($bufferAnalysis['excess'] <= 0) {
            return null;
        }

        $surplus = $bufferAnalysis['excess'];

        // Playbook allocation: 50% Reinvestment, 30% Reserve, 20% Profit
        $allocation = [
            'total_surplus' => round($surplus, 2),
            'reinvestment' => round($surplus * 0.50, 2),
            'reserve' => round($surplus * 0.30, 2),
            'profit_distribution' => round($surplus * 0.20, 2),
        ];

        // Suggest specific reinvestment opportunities
        $allocation['reinvestment_suggestions'] = $this->suggestReinvestmentOpportunities(
            $allocation['reinvestment']
        );

        return $allocation;
    }

    private function suggestReinvestmentOpportunities(float $reinvestmentAmount): array
    {
        $suggestions = [];

        if ($reinvestmentAmount >= 5000) {
            $suggestions[] = [
                'category' => 'equipment',
                'suggestion' => __('Kitchen equipment upgrade or expansion'),
                'estimated_cost' => 5000,
                'expected_roi' => __('Increased efficiency and capacity'),
            ];
        }

        if ($reinvestmentAmount >= 2000) {
            $suggestions[] = [
                'category' => 'marketing',
                'suggestion' => __('Digital marketing campaign'),
                'estimated_cost' => 2000,
                'expected_roi' => __('Customer acquisition and retention'),
            ];
        }

        if ($reinvestmentAmount >= 1000) {
            $suggestions[] = [
                'category' => 'technology',
                'suggestion' => __('POS system upgrade or software enhancement'),
                'estimated_cost' => 1000,
                'expected_roi' => __('Operational efficiency improvements'),
            ];
        }

        $suggestions[] = [
            'category' => 'inventory',
            'suggestion' => __('Strategic inventory investment'),
            'estimated_cost' => min($reinvestmentAmount, 3000),
            'expected_roi' => __('Better supplier terms and reduced stockouts'),
        ];

        return $suggestions;
    }

    private function generateCashFlowRecommendations(array $bufferAnalysis, array $trends): array
    {
        $recommendations = [];

        // Buffer-based recommendations
        switch ($bufferAnalysis['status']) {
            case 'critical':
                $recommendations[] = [
                    'priority' => 'critical',
                    'category' => 'cash_buffer',
                    'message' => __('Critical cash buffer shortage. Immediate action required.'),
                    'actions' => [
                        __('Accelerate accounts receivable collection'),
                        __('Negotiate extended payment terms with suppliers'),
                        __('Consider emergency financing options'),
                        __('Implement strict expense controls'),
                    ],
                ];

                break;

            case 'minimum':
                $recommendations[] = [
                    'priority' => 'high',
                    'category' => 'cash_buffer',
                    'message' => __('Cash buffer at minimum level. Build reserves.'),
                    'actions' => [
                        __('Focus on cash flow positive activities'),
                        __('Review and optimize payment cycles'),
                        __('Delay non-essential expenditures'),
                    ],
                ];

                break;
        }

        // Trend-based recommendations
        if ($trends['trend_direction'] === 'strong_decline') {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'revenue_trend',
                'message' => __('Revenue trend showing strong decline. Investigate causes.'),
                'actions' => [
                    __('Analyze customer feedback and market conditions'),
                    __('Review marketing and promotional strategies'),
                    __('Consider menu or pricing adjustments'),
                ],
            ];
        }

        return $recommendations;
    }

    private function generateCashFlowAlerts(array $bufferAnalysis, array $projections): array
    {
        $alerts = [];

        // Critical buffer alert
        if ($bufferAnalysis['status'] === 'critical') {
            $alerts[] = [
                'level' => 'critical',
                'type' => 'cash_buffer',
                'message' => __('Cash buffer below 2 months of expenses'),
                'value' => $bufferAnalysis['buffer_months'],
                'threshold' => 2.0,
            ];
        }

        // Negative cash flow projection alert
        if ($projections['monthly_net_cash_flow'] < 0) {
            $alerts[] = [
                'level' => 'warning',
                'type' => 'negative_cash_flow',
                'message' => __('Projected negative monthly cash flow'),
                'value' => $projections['monthly_net_cash_flow'],
                'threshold' => 0,
            ];
        }

        // Cash runway alert
        if (isset($projections['cash_runway_months']) && $projections['cash_runway_months'] < 6) {
            $alerts[] = [
                'level' => 'critical',
                'type' => 'cash_runway',
                'message' => __('Cash runway less than 6 months'),
                'value' => $projections['cash_runway_months'],
                'threshold' => 6.0,
            ];
        }

        return $alerts;
    }

    private function suggestManagementActions(array $bufferAnalysis): array
    {
        $actions = [];

        // Daily actions
        $actions['daily'] = [
            __('Monitor cash balance and daily sales performance'),
            __('Review pending payments and follow up on overdue accounts'),
            __('Track daily expenses against budget'),
        ];

        // Weekly actions
        $actions['weekly'] = [
            __('Review weekly cash flow vs projections'),
            __('Analyze sales trends and identify patterns'),
            __('Update cash flow projections based on current performance'),
        ];

        // Monthly actions
        $actions['monthly'] = [
            __('Comprehensive cash flow analysis and buffer assessment'),
            __('Review and adjust expense budgets if necessary'),
            __('Evaluate surplus allocation opportunities'),
        ];

        // Conditional actions based on status
        if ($bufferAnalysis['status'] === 'critical') {
            $actions['immediate'] = [
                __('Emergency cash flow meeting with key stakeholders'),
                __('Implement immediate cost reduction measures'),
                __('Explore emergency funding options'),
            ];
        }

        if ($bufferAnalysis['excess'] > 0) {
            $actions['surplus_management'] = [
                __('Evaluate reinvestment opportunities'),
                __('Consider building additional reserves'),
                __('Plan profit distribution strategy'),
            ];
        }

        return $actions;
    }
}
