<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use Carbon\Carbon;

/**
 * Calculate Cash Flow with Buffer Management
 *
 * Formula: CashFlow = TotalCashIn - TotalCashOut
 * Buffer Target: 2-3 months of expenses
 * Surplus Allocation: 50% Reinvestment, 30% Reserve, 20% Profit
 */
final class CalculateCashFlowAction
{
    public function __invoke(Carbon $dateFrom, Carbon $dateTo, array $expenses = [], float $currentCashBalance = 0): array
    {
        // Calculate cash inflows
        $cashInflows = $this->calculateCashInflows($dateFrom, $dateTo);

        // Calculate cash outflows
        $cashOutflows = $this->calculateCashOutflows($expenses, $dateFrom, $dateTo);

        // Calculate net cash flow
        $netCashFlow = $cashInflows['total'] - $cashOutflows['total'];

        // Calculate buffer requirements (2-3 months of expenses)
        $monthlyExpenses = $cashOutflows['monthly_average'];
        $bufferRequirement = $this->calculateBufferRequirement($monthlyExpenses);

        // Determine cash position
        $cashPosition = $this->analyzeCashPosition($currentCashBalance, $bufferRequirement, $netCashFlow);

        // Calculate surplus allocation if positive cash flow
        $surplusAllocation = $netCashFlow > 0
            ? $this->calculateSurplusAllocation($netCashFlow)
            : null;

        return [
            'cash_inflows' => $cashInflows,
            'cash_outflows' => $cashOutflows,
            'net_cash_flow' => round($netCashFlow, 2),
            'current_cash_balance' => round($currentCashBalance, 2),
            'projected_cash_balance' => round($currentCashBalance + $netCashFlow, 2),
            'buffer_requirement' => $bufferRequirement,
            'cash_position' => $cashPosition,
            'surplus_allocation' => $surplusAllocation,
            'recommendations' => $this->getRecommendations($cashPosition, $netCashFlow),
            'period' => [
                'from' => $dateFrom->toDateString(),
                'to' => $dateTo->toDateString(),
                'days' => $dateTo->diffInDays($dateFrom) + 1,
            ],
            'calculated_at' => now()->toISOString(),
        ];
    }

    private function calculateCashInflows(Carbon $dateFrom, Carbon $dateTo): array
    {
        // Revenue from completed sales
        $saleRevenue = Sale::query()->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('total_amount');

        // Revenue from paid sales (including partial payments)
        $paidRevenue = Sale::query()->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('payment_status', ['paid', 'partially_paid'])
            ->sum('total_amount');

        $daysDiff = $dateTo->diffInDays($dateFrom) + 1;

        return [
            'sale_revenue' => round($saleRevenue, 2),
            'paid_revenue' => round($paidRevenue, 2),
            'total' => round($paidRevenue, 2), // Use paid revenue as actual cash inflow
            'daily_average' => round($paidRevenue / $daysDiff, 2),
            'monthly_average' => round(($paidRevenue / $daysDiff) * 30, 2),
        ];
    }

    private function calculateCashOutflows(array $expenses, Carbon $dateFrom, Carbon $dateTo): array
    {
        $daysDiff = $dateTo->diffInDays($dateFrom) + 1;

        $expenseCategories = [
            'cogs' => $expenses['cogs'] ?? 0,
            'labor_costs' => $expenses['labor_costs'] ?? 0,
            'rent' => $expenses['rent'] ?? 0,
            'utilities' => $expenses['utilities'] ?? 0,
            'marketing' => $expenses['marketing'] ?? 0,
            'insurance' => $expenses['insurance'] ?? 0,
            'maintenance' => $expenses['maintenance'] ?? 0,
            'supplies' => $expenses['supplies'] ?? 0,
            'other' => $expenses['other'] ?? 0,
        ];

        $totalOutflows = array_sum($expenseCategories);

        return [
            'breakdown' => $expenseCategories,
            'total' => round($totalOutflows, 2),
            'daily_average' => round($totalOutflows / $daysDiff, 2),
            'monthly_average' => round(($totalOutflows / $daysDiff) * 30, 2),
        ];
    }

    private function calculateBufferRequirement(float $monthlyExpenses): array
    {
        return [
            'minimum_buffer' => round($monthlyExpenses * 2, 2), // 2 months
            'target_buffer' => round($monthlyExpenses * 2.5, 2), // 2.5 months
            'optimal_buffer' => round($monthlyExpenses * 3, 2), // 3 months
            'monthly_expenses' => round($monthlyExpenses, 2),
        ];
    }

    private function analyzeCashPosition(float $currentBalance, array $bufferRequirement, float $netCashFlow): array
    {
        $bufferRatio = $currentBalance / $bufferRequirement['target_buffer'];

        $status = match (true) {
            $currentBalance >= $bufferRequirement['optimal_buffer'] => 'excellent',
            $currentBalance >= $bufferRequirement['target_buffer'] => 'good',
            $currentBalance >= $bufferRequirement['minimum_buffer'] => 'warning',
            default => 'critical'
        };

        return [
            'status' => $status,
            'buffer_ratio' => round($bufferRatio, 2),
            'buffer_months' => round($currentBalance / $bufferRequirement['monthly_expenses'], 1),
            'is_cash_flow_positive' => $netCashFlow > 0,
            'runway_days' => $netCashFlow < 0
                ? round($currentBalance / abs($netCashFlow / 30), 0)
                : null,
        ];
    }

    private function calculateSurplusAllocation(float $surplus): array
    {
        // Playbook allocation: 50% Reinvestment, 30% Reserve, 20% Profit
        return [
            'total_surplus' => round($surplus, 2),
            'reinvestment' => round($surplus * 0.50, 2),
            'reserve' => round($surplus * 0.30, 2),
            'profit_distribution' => round($surplus * 0.20, 2),
            'allocation_percentages' => [
                'reinvestment' => 50,
                'reserve' => 30,
                'profit_distribution' => 20,
            ],
        ];
    }

    private function getRecommendations(array $cashPosition, float $netCashFlow): array
    {
        $recommendations = [];

        if ($netCashFlow < 0) {
            $recommendations[] = __('Negative cash flow detected. Reduce expenses or boost sales immediately.');
        }

        switch ($cashPosition['status']) {
            case 'critical':
                $recommendations[] = __('Critical cash position. Implement emergency cost reduction measures.');
                $recommendations[] = __('Consider short-term financing options to maintain operations.');

                break;
            case 'warning':
                $recommendations[] = __('Cash buffer below target. Focus on improving cash flow.');
                $recommendations[] = __('Review and optimize payment collection processes.');

                break;
            case 'good':
                $recommendations[] = __('Good cash position. Maintain current financial discipline.');

                break;
            case 'excellent':
                $recommendations[] = __('Excellent cash position. Consider strategic investment opportunities.');

                break;
        }

        if ($cashPosition['runway_days'] && $cashPosition['runway_days'] < 90) {
            $recommendations[] = __('Cash runway is less than 90 days. Take immediate action.');
        }

        return $recommendations;
    }
}
