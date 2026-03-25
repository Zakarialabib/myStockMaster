<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Generate Comprehensive Financial KPI Report
 *
 * Combines all financial KPIs from the RestoPos Management Playbook:
 * - Gross Profit Margin (Target: 60-70%)
 * - Net Profit Margin (Target: 15-25%)
 * - Cash Flow Analysis with Buffer Management
 * - Break-Even Analysis
 * - Additional KPIs: AOV, Labor Cost Ratio, Food Cost Ratio
 */
final class GenerateFinancialKpiReportAction
{
    public function __construct(
        private CalculateGrossMarginAction $grossMarginAction,
        private CalculateNetMarginAction $netMarginAction,
        private CalculateCashFlowAction $cashFlowAction,
        private CalculateBreakEvenAction $breakEvenAction
    ) {}

    public function __invoke(
        Carbon $dateFrom,
        Carbon $dateTo,
        array $expenses = [],
        array $fixedCosts = [],
        float $currentCashBalance = 0,
        bool $useCache = true
    ): array {
        $cacheKey = $this->generateCacheKey($dateFrom, $dateTo, $expenses, $fixedCosts, $currentCashBalance);

        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Calculate all KPIs
        $grossMargin = ($this->grossMarginAction)($dateFrom, $dateTo);
        $netMargin = ($this->netMarginAction)($dateFrom, $dateTo, $expenses);
        $cashFlow = ($this->cashFlowAction)($dateFrom, $dateTo, $expenses, $currentCashBalance);
        $breakEven = ($this->breakEvenAction)($fixedCosts);

        // Calculate additional KPIs
        $additionalKpis = $this->calculateAdditionalKpis($grossMargin, $netMargin, $expenses);

        // Generate overall financial health score
        $healthScore = $this->calculateFinancialHealthScore([
            'gross_margin' => $grossMargin,
            'net_margin' => $netMargin,
            'cash_flow' => $cashFlow,
            'break_even' => $breakEven,
            'additional_kpis' => $additionalKpis,
        ]);

        // Generate actionable recommendations
        $recommendations = $this->generateRecommendations([
            'gross_margin' => $grossMargin,
            'net_margin' => $netMargin,
            'cash_flow' => $cashFlow,
            'break_even' => $breakEven,
            'health_score' => $healthScore,
        ]);

        $report = [
            'summary' => [
                'period' => [
                    'from' => $dateFrom->toDateString(),
                    'to' => $dateTo->toDateString(),
                    'days' => $dateTo->diffInDays($dateFrom) + 1,
                ],
                'financial_health_score' => $healthScore,
                'overall_status' => $this->determineOverallStatus($healthScore['score']),
                'key_metrics' => [
                    'gross_margin_percentage' => $grossMargin['gross_margin_percentage'],
                    'net_margin_percentage' => $netMargin['net_margin_percentage'],
                    'net_cash_flow' => $cashFlow['net_cash_flow'],
                    'break_even_status' => $breakEven['current_performance']['status'] ?? 'unknown',
                ],
            ],
            'kpis' => [
                'gross_margin' => $grossMargin,
                'net_margin' => $netMargin,
                'cash_flow' => $cashFlow,
                'break_even' => $breakEven,
                'additional_kpis' => $additionalKpis,
            ],
            'recommendations' => $recommendations,
            'alerts' => $this->generateAlerts([
                'gross_margin' => $grossMargin,
                'net_margin' => $netMargin,
                'cash_flow' => $cashFlow,
                'break_even' => $breakEven,
            ]),
            'generated_at' => now()->toISOString(),
        ];

        // Cache for 1 hour
        if ($useCache) {
            Cache::put($cacheKey, $report, now()->addHour());
        }

        return $report;
    }

    private function calculateAdditionalKpis(array $grossMargin, array $netMargin, array $expenses): array
    {
        $totalRevenue = $grossMargin['total_revenue'];

        // Calculate labor cost ratio (Target: 20-30%)
        $laborCosts = $expenses['labor_costs'] ?? 0;
        $laborCostRatio = $totalRevenue > 0 ? ($laborCosts / $totalRevenue) * 100 : 0;

        // Calculate food cost ratio (Target: 25-35%)
        $foodCosts = $grossMargin['total_cogs'];
        $foodCostRatio = $totalRevenue > 0 ? ($foodCosts / $totalRevenue) * 100 : 0;

        // Calculate average sale value from revenue and estimated sales
        $estimatedSales = $totalRevenue > 0 ? max(1, round($totalRevenue / 25)) : 0; // Assume $25 AOV baseline
        $averageSaleValue = $estimatedSales > 0 ? $totalRevenue / $estimatedSales : 0;

        return [
            'labor_cost_ratio' => [
                'percentage' => round($laborCostRatio, 2),
                'amount' => round($laborCosts, 2),
                'target_min' => 20.0,
                'target_max' => 30.0,
                'status' => $this->determineRatioStatus($laborCostRatio, 20, 30),
            ],
            'food_cost_ratio' => [
                'percentage' => round($foodCostRatio, 2),
                'amount' => round($foodCosts, 2),
                'target_min' => 25.0,
                'target_max' => 35.0,
                'status' => $this->determineRatioStatus($foodCostRatio, 25, 35),
            ],
            'average_order_value' => [
                'amount' => round($averageSaleValue, 2),
                'estimated_orders' => $estimatedSales,
                'total_revenue' => round($totalRevenue, 2),
            ],
        ];
    }

    private function determineRatioStatus(float $ratio, float $targetMin, float $targetMax): string
    {
        return match (true) {
            $ratio <= $targetMax && $ratio >= $targetMin => 'good',
            $ratio < $targetMin => 'excellent',
            $ratio <= $targetMax + 5 => 'warning',
            default => 'critical'
        };
    }

    private function calculateFinancialHealthScore(array $kpis): array
    {
        $scores = [];
        $weights = [];

        // Gross Margin Score (Weight: 25%)
        $grossMarginScore = $this->scoreMetric(
            $kpis['gross_margin']['gross_margin_percentage'],
            60,
            70,
            100
        );
        $scores['gross_margin'] = $grossMarginScore;
        $weights['gross_margin'] = 0.25;

        // Net Margin Score (Weight: 30%)
        $netMarginScore = $this->scoreMetric(
            $kpis['net_margin']['net_margin_percentage'],
            15,
            25,
            100
        );
        $scores['net_margin'] = $netMarginScore;
        $weights['net_margin'] = 0.30;

        // Cash Flow Score (Weight: 25%)
        $cashFlowScore = $kpis['cash_flow']['net_cash_flow'] > 0 ? 100 : 0;

        if ($kpis['cash_flow']['cash_position']['status'] === 'excellent') {
            $cashFlowScore = 100;
        } elseif ($kpis['cash_flow']['cash_position']['status'] === 'good') {
            $cashFlowScore = 80;
        } elseif ($kpis['cash_flow']['cash_position']['status'] === 'warning') {
            $cashFlowScore = 60;
        } else {
            $cashFlowScore = 30;
        }

        $scores['cash_flow'] = $cashFlowScore;
        $weights['cash_flow'] = 0.25;

        // Break Even Score (Weight: 20%)
        $breakEvenScore = 50; // Default

        if (isset($kpis['break_even']['current_performance']['status'])) {
            $breakEvenScore = match ($kpis['break_even']['current_performance']['status']) {
                'excellent' => 100,
                'good' => 85,
                'marginal' => 70,
                'break_even' => 60,
                'below_break_even' => 30,
                default => 50
            };
        }
        $scores['break_even'] = $breakEvenScore;
        $weights['break_even'] = 0.20;

        // Calculate weighted average
        $totalScore = 0;

        foreach ($scores as $metric => $score) {
            $totalScore += $score * $weights[$metric];
        }

        return [
            'score' => round($totalScore, 1),
            'grade' => $this->getHealthGrade($totalScore),
            'breakdown' => $scores,
            'weights' => $weights,
        ];
    }

    private function scoreMetric(float $value, float $targetMin, float $targetMax, float $maxScore): float
    {
        if ($value >= $targetMin && $value <= $targetMax) {
            return $maxScore;
        }

        if ($value > $targetMax) {
            return min($maxScore, $maxScore * (1 + ($value - $targetMax) / $targetMax * 0.2));
        }

        if ($value < $targetMin) {
            return max(0, $maxScore * ($value / $targetMin));
        }

        return 0;
    }

    private function getHealthGrade(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'F'
        };
    }

    private function determineOverallStatus(float $score): string
    {
        return match (true) {
            $score >= 85 => 'excellent',
            $score >= 70 => 'good',
            $score >= 55 => 'warning',
            default => 'critical'
        };
    }

    private function generateRecommendations(array $data): array
    {
        $recommendations = [];

        // Priority recommendations based on health score
        if ($data['health_score']['score'] < 60) {
            $recommendations[] = [
                'priority' => 'critical',
                'category' => 'overall',
                'message' => __('Financial health is critical. Immediate comprehensive review required.'),
                'actions' => [
                    __('Schedule emergency financial review meeting'),
                    __('Implement cost reduction measures'),
                    __('Review pricing strategy'),
                ],
            ];
        }

        // Specific KPI recommendations
        if ($data['gross_margin']['status'] === 'critical') {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'gross_margin',
                'message' => $data['gross_margin']['recommendation'],
                'actions' => [
                    __('Review supplier contracts and negotiate better rates'),
                    __('Analyze menu pricing strategy'),
                    __('Implement portion control measures'),
                ],
            ];
        }

        if ($data['cash_flow']['cash_position']['status'] === 'critical') {
            $recommendations[] = [
                'priority' => 'critical',
                'category' => 'cash_flow',
                'message' => __('Critical cash position requires immediate attention'),
                'actions' => [
                    __('Accelerate accounts receivable collection'),
                    __('Negotiate extended payment terms with suppliers'),
                    __('Consider short-term financing options'),
                ],
            ];
        }

        return $recommendations;
    }

    private function generateAlerts(array $kpis): array
    {
        $alerts = [];

        // Critical alerts
        if ($kpis['cash_flow']['net_cash_flow'] < 0) {
            $alerts[] = [
                'level' => 'critical',
                'message' => __('Negative cash flow detected'),
                'metric' => 'cash_flow',
            ];
        }

        if ($kpis['gross_margin']['gross_margin_percentage'] < 50) {
            $alerts[] = [
                'level' => 'critical',
                'message' => __('Gross margin below 50%'),
                'metric' => 'gross_margin',
            ];
        }

        // Warning alerts
        if ($kpis['net_margin']['net_margin_percentage'] < 10) {
            $alerts[] = [
                'level' => 'warning',
                'message' => __('Net margin below 10%'),
                'metric' => 'net_margin',
            ];
        }

        return $alerts;
    }

    private function generateCacheKey(Carbon $dateFrom, Carbon $dateTo, array $expenses, array $fixedCosts, float $currentCashBalance): string
    {
        return 'financial_kpi_report_' . md5(
            $dateFrom->toDateString() .
            $dateTo->toDateString() .
            serialize($expenses) .
            serialize($fixedCosts) .
            $currentCashBalance
        );
    }
}
