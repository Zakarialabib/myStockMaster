<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Calculate Net Profit Margin
 *
 * Formula: Net Profit ÷ Revenue
 * Target: 15-25% according to RestoPos Management Playbook
 */
final class CalculateNetMarginAction
{
    public function __invoke(Carbon $dateFrom, Carbon $dateTo, array $expenses = []): array
    {
        // Get total revenue from completed sales
        $totalRevenue = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Calculate total COGS (Cost of Goods Sold)
        $totalCogs = SaleDetails::whereHas('sale', function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled');
        })
            ->sum(DB::raw('cost * quantity'));

        // Calculate operating expenses
        $operatingExpenses = $this->calculateOperatingExpenses($expenses, $dateFrom, $dateTo);

        // Calculate net profit
        $grossProfit = $totalRevenue - $totalCogs;
        $netProfit = $grossProfit - $operatingExpenses['total'];

        // Calculate net margin percentage
        $netMarginPercentage = $totalRevenue > 0
            ? ($netProfit / $totalRevenue) * 100
            : 0;

        // Determine status based on target (15-25%)
        $status = $this->determineMarginStatus($netMarginPercentage);

        return [
            'total_revenue'         => round($totalRevenue, 2),
            'total_cogs'            => round($totalCogs, 2),
            'gross_profit'          => round($grossProfit, 2),
            'operating_expenses'    => $operatingExpenses,
            'net_profit'            => round($netProfit, 2),
            'net_margin_percentage' => round($netMarginPercentage, 2),
            'target_min'            => 15.0,
            'target_max'            => 25.0,
            'status'                => $status,
            'recommendation'        => $this->getRecommendation($netMarginPercentage),
            'period'                => [
                'from' => $dateFrom->toDateString(),
                'to'   => $dateTo->toDateString(),
                'days' => $dateTo->diffInDays($dateFrom) + 1,
            ],
            'calculated_at' => now()->toISOString(),
        ];
    }

    private function calculateOperatingExpenses(array $expenses, Carbon $dateFrom, Carbon $dateTo): array
    {
        $daysDiff = $dateTo->diffInDays($dateFrom) + 1;

        // Default expense categories with estimated percentages of revenue
        $defaultExpenses = [
            'labor_costs' => $expenses['labor_costs'] ?? 0,
            'rent'        => $expenses['rent'] ?? 0,
            'utilities'   => $expenses['utilities'] ?? 0,
            'marketing'   => $expenses['marketing'] ?? 0,
            'insurance'   => $expenses['insurance'] ?? 0,
            'maintenance' => $expenses['maintenance'] ?? 0,
            'other'       => $expenses['other'] ?? 0,
        ];

        $total = array_sum($defaultExpenses);

        return [
            'breakdown'     => $defaultExpenses,
            'total'         => round($total, 2),
            'daily_average' => round($total / $daysDiff, 2),
        ];
    }

    private function determineMarginStatus(float $marginPercentage): string
    {
        return match (true) {
            $marginPercentage >= 25 => 'excellent',
            $marginPercentage >= 15 => 'good',
            $marginPercentage >= 10 => 'warning',
            $marginPercentage >= 0  => 'critical',
            default                 => 'loss'
        };
    }

    private function getRecommendation(float $marginPercentage): string
    {
        return match (true) {
            $marginPercentage >= 25 => __('Excellent net margin! Consider expansion opportunities.'),
            $marginPercentage >= 15 => __('Good net margin within target range. Maintain current operations.'),
            $marginPercentage >= 10 => __('Below target. Review operating expenses and optimize efficiency.'),
            $marginPercentage >= 0  => __('Critical margin. Immediate cost reduction or revenue increase needed.'),
            default                 => __('Operating at a loss. Emergency financial restructuring required.')
        };
    }
}
