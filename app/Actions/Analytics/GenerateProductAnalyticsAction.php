<?php

declare(strict_types=1);

namespace App\Actions\Analytics;

use App\Models\Product;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

final class GenerateProductAnalyticsAction
{
    public function __invoke(Product $product, array $options = []): array
    {
        $dateFrom = $options['date_from'] ?? Carbon::now()->subDays(30);
        $dateTo = $options['date_to'] ?? Carbon::now();
        $useCache = $options['use_cache'] ?? true;
        $cacheTtl = $options['cache_ttl'] ?? 3600; // 1 hour

        $cacheKey = $this->generateCacheKey($product->id, $dateFrom, $dateTo);

        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $analytics = $this->calculateAnalytics($product, $dateFrom, $dateTo);

        if ($useCache) {
            Cache::put($cacheKey, $analytics, $cacheTtl);
        }

        return $analytics;
    }

    /** Clear analytics cache for a specific product */
    public function clearCache(Product $product): void
    {
        $pattern = "product_analytics:{$product->id}:*";

        // This is a simplified cache clearing - in production you might want
        // to use a more sophisticated cache tagging system
        Cache::flush(); // Or implement pattern-based cache clearing
    }

    private function calculateAnalytics(Product $product, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Base query for sale items within date range
        $baseQuery = SaleDetails::where('product_id', $product->id)
            ->whereHas('sale', function ($query) use ($dateFrom, $dateTo): void {
                $query->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->where('status', '!=', 'cancelled');
            });

        // Get basic sales statistics
        $salesStats = $this->getSalesStatistics($baseQuery);

        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics($product, $baseQuery, $dateFrom, $dateTo);

        // Get trend analysis
        $trendAnalysis = $this->getTrendAnalysis($product, $dateFrom, $dateTo);

        // Get profitability analysis
        $profitabilityAnalysis = $this->getProfitabilityAnalysis($baseQuery, $product);

        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'date_range' => [
                'from' => $dateFrom->toDateString(),
                'to' => $dateTo->toDateString(),
            ],
            'sales_statistics' => $salesStats,
            'performance_metrics' => $performanceMetrics,
            'trend_analysis' => $trendAnalysis,
            'profitability_analysis' => $profitabilityAnalysis,
            'generated_at' => now()->toISOString(),
        ];
    }

    private function getSalesStatistics($baseQuery): array
    {
        $stats = $baseQuery->selectRaw('
            COUNT(*) as total_orders,
            SUM(quantity) as total_quantity_sold,
            SUM(price * quantity) as total_revenue,
            AVG(price * quantity) as average_order_value,
            MIN(price * quantity) as min_order_value,
            MAX(price * quantity) as max_order_value
        ')->first();

        return [
            'total_orders' => (int) ($stats->total_orders ?? 0),
            'total_quantity_sold' => (int) ($stats->total_quantity_sold ?? 0),
            'total_revenue' => (float) ($stats->total_revenue ?? 0),
            'average_order_value' => (float) ($stats->average_order_value ?? 0),
            'min_order_value' => (float) ($stats->min_order_value ?? 0),
            'max_order_value' => (float) ($stats->max_order_value ?? 0),
        ];
    }

    private function getPerformanceMetrics(Product $product, $baseQuery, Carbon $dateFrom, Carbon $dateTo): array
    {
        $daysDiff = $dateTo->diffInDays($dateFrom) ?: 1;
        $totalQuantity = $baseQuery->sum('quantity') ?? 0;
        $totalSales = $baseQuery->count() ?? 0;

        // Get current stock level
        $currentStock = $product->stock?->quantity ?? 0;
        $minimumStock = $product->stock?->minimum_quantity ?? 0;

        // Calculate stock turnover rate
        $averageStock = ($currentStock + $totalQuantity) / 2;
        $stockTurnoverRate = $averageStock > 0 ? $totalQuantity / $averageStock : 0;

        return [
            'daily_average_sales' => $totalQuantity / $daysDiff,
            'daily_average_orders' => $totalSales / $daysDiff,
            'stock_turnover_rate' => round($stockTurnoverRate, 2),
            'current_stock_level' => $currentStock,
            'minimum_stock_level' => $minimumStock,
            'stock_status' => $this->getStockStatus($currentStock, $minimumStock),
            'days_of_stock_remaining' => $this->calculateDaysOfStockRemaining($currentStock, $totalQuantity, $daysDiff),
        ];
    }

    private function getTrendAnalysis(Product $product, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Get daily sales data
        $dailySales = SaleDetails::where('product_id', $product->id)
            ->whereHas('sale', function ($query) use ($dateFrom, $dateTo): void {
                $query->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->where('status', '!=', 'cancelled');
            })
            ->selectRaw('DATE(sales.created_at) as sale_date, SUM(quantity) as daily_quantity, SUM(price * quantity) as daily_revenue')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        $trend = $this->calculateTrend($dailySales->pluck('daily_quantity')->toArray());

        return [
            'daily_sales_data' => $dailySales->toArray(),
            'trend_direction' => $trend['direction'],
            'trend_strength' => $trend['strength'],
            'best_selling_day' => $dailySales->sortByDesc('daily_quantity')->first()?->sale_date,
            'worst_selling_day' => $dailySales->sortBy('daily_quantity')->first()?->sale_date,
        ];
    }

    private function getProfitabilityAnalysis($baseQuery, Product $product): array
    {
        $items = $baseQuery->get();

        $totalRevenue = $items->sum(fn ($item) => $item->price * $item->quantity);
        $totalCost = $items->sum(fn ($item) => ($item->cost ?? $product->cost) * $item->quantity);
        $totalProfit = $totalRevenue - $totalCost;

        $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
        $markupPercentage = $totalCost > 0 ? (($totalRevenue - $totalCost) / $totalCost) * 100 : 0;

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_cost' => round($totalCost, 2),
            'total_profit' => round($totalProfit, 2),
            'profit_margin_percentage' => round($profitMargin, 2),
            'markup_percentage' => round($markupPercentage, 2),
            'average_profit_per_unit' => $items->count() > 0 ? round($totalProfit / $items->sum('quantity'), 2) : 0,
        ];
    }

    private function calculateTrend(array $values): array
    {
        if (count($values) < 2) {
            return ['direction' => 'stable', 'strength' => 0];
        }

        $n = count($values);
        $sumX = array_sum(range(1, $n));
        $sumY = array_sum($values);
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1;
            $y = $values[$i];
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);

        $direction = $slope > 0.1 ? 'increasing' : ($slope < -0.1 ? 'decreasing' : 'stable');
        $strength = abs($slope);

        return [
            'direction' => $direction,
            'strength' => round($strength, 3),
        ];
    }

    private function getStockStatus(int $currentStock, int $minimumStock): string
    {
        if ($currentStock <= 0) {
            return 'out_of_stock';
        }

        if ($minimumStock > 0 && $currentStock <= $minimumStock) {
            return 'low_stock';
        }

        if ($minimumStock > 0 && $currentStock <= ($minimumStock * 1.5)) {
            return 'warning';
        }

        return 'healthy';
    }

    private function calculateDaysOfStockRemaining(int $currentStock, int $totalSold, int $daysPeriod): int
    {
        if ($totalSold <= 0) {
            return 999; // Essentially infinite if no sales
        }

        $dailyAverageSales = $totalSold / $daysPeriod;

        return (int) ceil($currentStock / $dailyAverageSales);
    }

    private function generateCacheKey(int $productId, Carbon $dateFrom, Carbon $dateTo): string
    {
        return "product_analytics:{$productId}:{$dateFrom->format('Y-m-d')}:{$dateTo->format('Y-m-d')}";
    }
}

/*
|--------------------------------------------------------------------------
| USAGE EXAMPLES
|--------------------------------------------------------------------------
|
| How to call from a Livewire component:
| $analytics = resolve(GenerateProductAnalyticsAction::class)($product, [
|     'date_from' => Carbon::now()->subDays(7),
|     'date_to' => Carbon::now(),
|     'use_cache' => true
| ]);
|
| Get analytics for last 30 days (default):
| $analytics = resolve(GenerateProductAnalyticsAction::class)($product);
|
| How to test:
| $product = Product::factory()->create();
| $analytics = resolve(GenerateProductAnalyticsAction::class)($product, [
|     'use_cache' => false
| ]);
| expect($analytics)->toHaveKey('sales_statistics');
| expect($analytics)->toHaveKey('performance_metrics');
*/
