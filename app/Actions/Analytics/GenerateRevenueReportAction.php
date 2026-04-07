<?php

declare(strict_types=1);

namespace App\Actions\Analytics;

use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

final class GenerateRevenueReportAction
{
    public function __invoke(array $options = []): array
    {
        $dateFrom = $options['date_from'] ?? Carbon::now()->subDays(30);
        $dateTo = $options['date_to'] ?? Carbon::now();
        $groupBy = $options['group_by'] ?? 'day'; // day, week, month, year
        $includeProducts = $options['include_products'] ?? false;
        $includeCategories = $options['include_categories'] ?? false;
        $useCache = $options['use_cache'] ?? true;
        $cacheTtl = $options['cache_ttl'] ?? 1800; // 30 minutes

        $cacheKey = $this->generateCacheKey($dateFrom, $dateTo, $groupBy, $includeProducts, $includeCategories);

        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $report = $this->generateReport($dateFrom, $dateTo, $groupBy, $includeProducts, $includeCategories);

        if ($useCache) {
            Cache::put($cacheKey, $report, $cacheTtl);
        }

        return $report;
    }

    /** Clear revenue report cache */
    public function clearCache(): void
    {
        // This is a simplified cache clearing - in production you might want
        // to use a more sophisticated cache tagging system
        Cache::flush(); // Or implement pattern-based cache clearing
    }

    private function generateReport(
        Carbon $dateFrom,
        Carbon $dateTo,
        string $groupBy,
        bool $includeProducts,
        bool $includeCategories,
    ): array {
        // Get overall revenue statistics
        $overallStats = $this->getOverallStatistics($dateFrom, $dateTo);

        // Get time-based revenue breakdown
        $timeBreakdown = $this->getTimeBasedBreakdown($dateFrom, $dateTo, $groupBy);

        // Get payment method breakdown
        $paymentMethodBreakdown = $this->getPaymentMethodBreakdown($dateFrom, $dateTo);

        // Get sale status breakdown
        $orderStatusBreakdown = $this->getSaleStatusBreakdown($dateFrom, $dateTo);

        $report = [
            'report_period' => [
                'from' => $dateFrom->toDateString(),
                'to' => $dateTo->toDateString(),
                'days' => $dateTo->diffInDays($dateFrom) + 1,
            ],
            'overall_statistics' => $overallStats,
            'time_breakdown' => $timeBreakdown,
            'payment_method_breakdown' => $paymentMethodBreakdown,
            'order_status_breakdown' => $orderStatusBreakdown,
            'generated_at' => now()->toISOString(),
        ];

        // Add product breakdown if requested
        if ($includeProducts) {
            $report['product_breakdown'] = $this->getProductBreakdown($dateFrom, $dateTo);
        }

        // Add category breakdown if requested
        if ($includeCategories) {
            $report['category_breakdown'] = $this->getCategoryBreakdown($dateFrom, $dateTo);
        }

        return $report;
    }

    private function getOverallStatistics(Carbon $dateFrom, Carbon $dateTo): array
    {
        $stats = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                COUNT(*) as total_orders,
                COUNT(CASE WHEN status != "cancelled" THEN 1 END) as completed_orders,
                COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled_orders,
                SUM(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as total_revenue,
                SUM(CASE WHEN status != "cancelled" THEN tax_amount ELSE 0 END) as total_tax,
                SUM(CASE WHEN status != "cancelled" THEN subtotal_amount ELSE 0 END) as total_subtotal,
                AVG(CASE WHEN status != "cancelled" THEN total_amount ELSE NULL END) as average_order_value,
                MIN(CASE WHEN status != "cancelled" THEN total_amount ELSE NULL END) as min_order_value,
                MAX(CASE WHEN status != "cancelled" THEN total_amount ELSE NULL END) as max_order_value
            ')
            ->first();

        $totalItems = SaleDetails::whereHas('sale', function ($query) use ($dateFrom, $dateTo): void {
            $query->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled');
        })->sum('quantity');

        $daysDiff = $dateTo->diffInDays($dateFrom) + 1;

        return [
            'total_orders' => (int) ($stats->total_orders ?? 0),
            'completed_orders' => (int) ($stats->completed_orders ?? 0),
            'cancelled_orders' => (int) ($stats->cancelled_orders ?? 0),
            'total_revenue' => (float) ($stats->total_revenue ?? 0),
            'total_tax' => (float) ($stats->total_tax ?? 0),
            'total_subtotal' => (float) ($stats->total_subtotal ?? 0),
            'total_items_sold' => (int) $totalItems,
            'average_order_value' => (float) ($stats->average_order_value ?? 0),
            'min_order_value' => (float) ($stats->min_order_value ?? 0),
            'max_order_value' => (float) ($stats->max_order_value ?? 0),
            'daily_average_revenue' => (float) (($stats->total_revenue ?? 0) / $daysDiff),
            'daily_average_orders' => (float) (($stats->completed_orders ?? 0) / $daysDiff),
            'cancellation_rate' => $stats->total_orders > 0 ? round(($stats->cancelled_orders / $stats->total_orders) * 100, 2) : 0,
        ];
    }

    private function getTimeBasedBreakdown(Carbon $dateFrom, Carbon $dateTo, string $groupBy): array
    {
        $dateFormat = match ($groupBy) {
            'hour' => '%Y-%m-%d %H:00:00',
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d',
        };

        $dateFormatSql = db_date_format('created_at', $dateFormat);

        $breakdown = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled')
            ->selectRaw("
                {$dateFormatSql} as period,
                COUNT(*) as order_count,
                SUM(total_amount) as revenue,
                SUM(tax_amount) as tax,
                AVG(total_amount) as avg_order_value
            ")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => $item->period,
                    'order_count' => (int) $item->order_count,
                    'revenue' => (float) $item->revenue,
                    'tax' => (float) $item->tax,
                    'avg_order_value' => (float) $item->avg_order_value,
                ];
            })
            ->toArray();

        return [
            'group_by' => $groupBy,
            'data' => $breakdown,
            'summary' => [
                'total_periods' => count($breakdown),
                'best_period' => collect($breakdown)->sortByDesc('revenue')->first(),
                'worst_period' => collect($breakdown)->sortBy('revenue')->first(),
            ],
        ];
    }

    private function getPaymentMethodBreakdown(Carbon $dateFrom, Carbon $dateTo): array
    {
        $breakdown = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled')
            ->where('is_paid', true)
            ->selectRaw('
                COALESCE(payment_method, "unknown") as payment_method,
                COUNT(*) as order_count,
                SUM(total_amount) as revenue,
                AVG(total_amount) as avg_order_value
            ')
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($item) {
                return [
                    'payment_method' => $item->payment_method,
                    'order_count' => (int) $item->order_count,
                    'revenue' => (float) $item->revenue,
                    'avg_order_value' => (float) $item->avg_order_value,
                ];
            })
            ->toArray();

        $totalRevenue = collect($breakdown)->sum('revenue');

        return [
            'data' => collect($breakdown)->map(function ($item) use ($totalRevenue) {
                $item['percentage'] = $totalRevenue > 0 ? round(($item['revenue'] / $totalRevenue) * 100, 2) : 0;

                return $item;
            })->toArray(),
            'most_popular_method' => collect($breakdown)->sortByDesc('order_count')->first()['payment_method'] ?? 'N/A',
            'highest_revenue_method' => collect($breakdown)->sortByDesc('revenue')->first()['payment_method'] ?? 'N/A',
        ];
    }

    private function getSaleStatusBreakdown(Carbon $dateFrom, Carbon $dateTo): array
    {
        $breakdown = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                status,
                COUNT(*) as order_count,
                SUM(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as revenue
            ')
            ->groupBy('status')
            ->orderByDesc('order_count')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'order_count' => (int) $item->order_count,
                    'revenue' => (float) $item->revenue,
                ];
            })
            ->toArray();

        $totalSales = collect($breakdown)->sum('order_count');

        return [
            'data' => collect($breakdown)->map(function ($item) use ($totalSales) {
                $item['percentage'] = $totalSales > 0 ? round(($item['order_count'] / $totalSales) * 100, 2) : 0;

                return $item;
            })->toArray(),
        ];
    }

    private function getProductBreakdown(Carbon $dateFrom, Carbon $dateTo): array
    {
        $breakdown = SaleDetails::whereHas('sale', function ($query) use ($dateFrom, $dateTo): void {
            $query->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled');
        })
            ->with('product')
            ->selectRaw('
                product_id,
                SUM(quantity) as total_quantity,
                SUM(price * quantity) as revenue,
                COUNT(DISTINCT order_id) as order_count,
                AVG(price) as avg_price
            ')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(20) // Top 20 products
            ->get()
            ->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name ?? 'Unknown Product',
                    'total_quantity' => (int) $item->total_quantity,
                    'revenue' => (float) $item->revenue,
                    'order_count' => (int) $item->order_count,
                    'avg_price' => (float) $item->avg_price,
                ];
            })
            ->toArray();

        return [
            'top_products' => $breakdown,
            'total_products_sold' => count($breakdown),
        ];
    }

    private function getCategoryBreakdown(Carbon $dateFrom, Carbon $dateTo): array
    {
        $breakdown = SaleDetails::whereHas('sale', function ($query) use ($dateFrom, $dateTo): void {
            $query->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', '!=', 'cancelled');
        })
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('
                categories.id as category_id,
                categories.name as category_name,
                SUM(sale_details.quantity) as total_quantity,
                SUM(sale_details.price * sale_details.quantity) as revenue,
                COUNT(DISTINCT sale_details.sale_id) as order_count
            ')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($item) {
                return [
                    'category_id' => $item->category_id,
                    'category_name' => $item->category_name,
                    'total_quantity' => (int) $item->total_quantity,
                    'revenue' => (float) $item->revenue,
                    'order_count' => (int) $item->order_count,
                ];
            })
            ->toArray();

        $totalRevenue = collect($breakdown)->sum('revenue');

        return [
            'data' => collect($breakdown)->map(function ($item) use ($totalRevenue) {
                $item['percentage'] = $totalRevenue > 0 ? round(($item['revenue'] / $totalRevenue) * 100, 2) : 0;

                return $item;
            })->toArray(),
            'top_category' => collect($breakdown)->sortByDesc('revenue')->first()['category_name'] ?? 'N/A',
        ];
    }

    private function generateCacheKey(
        Carbon $dateFrom,
        Carbon $dateTo,
        string $groupBy,
        bool $includeProducts,
        bool $includeCategories,
    ): string {
        $key = "revenue_report:{$dateFrom->format('Y-m-d')}:{$dateTo->format('Y-m-d')}:{$groupBy}";

        if ($includeProducts) {
            $key .= ':products';
        }

        if ($includeCategories) {
            $key .= ':categories';
        }

        return $key;
    }
}

/*
|--------------------------------------------------------------------------
| USAGE EXAMPLES
|--------------------------------------------------------------------------
|
| How to call from a Livewire component:
| $report = resolve(GenerateRevenueReportAction::class)([
|     'date_from' => Carbon::now()->subDays(7),
|     'date_to' => Carbon::now(),
|     'group_by' => 'day',
|     'include_products' => true,
|     'include_categories' => true
| ]);
|
| Get monthly report:
| $report = resolve(GenerateRevenueReportAction::class)([
|     'date_from' => Carbon::now()->startOfMonth(),
|     'date_to' => Carbon::now()->endOfMonth(),
|     'group_by' => 'day'
| ]);
|
| How to test:
| $report = resolve(GenerateRevenueReportAction::class)([
|     'date_from' => Carbon::now()->subDays(1),
|     'date_to' => Carbon::now(),
|     'use_cache' => false
| ]);
| expect($report)->toHaveKey('overall_statistics');
| expect($report)->toHaveKey('time_breakdown');
*/
