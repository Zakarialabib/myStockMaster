<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Calculate Gross Profit Margin
 *
 * Formula: (Revenue - COGS) ÷ Revenue
 * Target: 60-70% according to RestoPos Management Playbook
 */
final class CalculateGrossMarginAction
{
    public function __invoke(Carbon $dateFrom, Carbon $dateTo): array
    {
        // Get total revenue from completed sales
        $totalRevenue = Sale::query()->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Calculate total COGS (Cost of Goods Sold)
        $totalCogs = SaleDetails::query()->whereHas('sale', function (\Illuminate\Contracts\Database\Query\Builder $builder) use ($dateFrom, $dateTo): void {
            $builder->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled');
        })
            ->sum(DB::raw('cost * quantity'));

        // Calculate gross profit
        $grossProfit = $totalRevenue - $totalCogs;

        // Calculate gross margin percentage
        $grossMarginPercentage = $totalRevenue > 0
            ? ($grossProfit / $totalRevenue) * 100
            : 0;

        // Determine status based on target (60-70%)
        $status = $this->determineMarginStatus($grossMarginPercentage);

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_cogs' => round($totalCogs, 2),
            'gross_profit' => round($grossProfit, 2),
            'gross_margin_percentage' => round($grossMarginPercentage, 2),
            'target_min' => 60.0,
            'target_max' => 70.0,
            'status' => $status,
            'recommendation' => $this->getRecommendation($grossMarginPercentage),
            'period' => [
                'from' => $dateFrom->toDateString(),
                'to' => $dateTo->toDateString(),
                'days' => $dateTo->diffInDays($dateFrom) + 1,
            ],
            'calculated_at' => now()->toISOString(),
        ];
    }

    private function determineMarginStatus(float $marginPercentage): string
    {
        return match (true) {
            $marginPercentage >= 70 => 'excellent',
            $marginPercentage >= 60 => 'good',
            $marginPercentage >= 50 => 'warning',
            default => 'critical'
        };
    }

    private function getRecommendation(float $marginPercentage): string
    {
        return match (true) {
            $marginPercentage >= 70 => __('Excellent margin! Consider strategic reinvestment opportunities.'),
            $marginPercentage >= 60 => __('Good margin within target range. Monitor for consistency.'),
            $marginPercentage >= 50 => __('Below target. Review menu pricing or negotiate better supplier rates.'),
            $marginPercentage >= 30 => __('Critical margin. Immediate action required: increase prices or reduce COGS.'),
            default => __('Unsustainable margin. Emergency review of pricing strategy and cost structure needed.')
        };
    }
}
