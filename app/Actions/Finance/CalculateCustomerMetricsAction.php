<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Customer;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class CalculateCustomerMetricsAction
{
    /** Calculate comprehensive customer metrics including CLV and CAC */
    public function __invoke(array $params = []): array
    {
        $period = $params['period'] ?? 'monthly';
        $startDate = $params['start_date'] ?? Carbon::now()->subMonths(12);
        $endDate = $params['end_date'] ?? Carbon::now();
        $segmentBy = $params['segment_by'] ?? null; // 'acquisition_channel', 'customer_type', etc.

        return [
            'clv_metrics' => $this->calculateCLVMetrics($startDate, $endDate, $period),
            'cac_metrics' => $this->calculateCACMetrics($startDate, $endDate, $period),
            'customer_segments' => $this->calculateSegmentMetrics($startDate, $endDate, $segmentBy),
            'cohort_analysis' => $this->calculateCohortAnalysis($startDate, $endDate),
            'retention_metrics' => $this->calculateRetentionMetrics($startDate, $endDate),
            'ltv_cac_ratio' => $this->calculateLTVCACRatio($startDate, $endDate),
            'customer_acquisition_trends' => $this->calculateAcquisitionTrends($startDate, $endDate, $period),
            'revenue_per_customer' => $this->calculateRevenuePerCustomer($startDate, $endDate),
            'churn_analysis' => $this->calculateChurnAnalysis($startDate, $endDate),
            'customer_value_distribution' => $this->calculateValueDistribution($startDate, $endDate),
            'period' => $period,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'generated_at' => Carbon::now()->toISOString(),
        ];
    }

    /** Calculate Customer Lifetime Value (CLV) metrics */
    private function calculateCLVMetrics(Carbon $startDate, Carbon $endDate, string $period): array
    {
        // Get customer data with sales
        $customers = Customer::with(['sales' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        }])->get();

        $totalCustomers = $customers->count();
        $activeCustomers = $customers->filter(fn ($c) => $c->sales->count() > 0)->count();

        // Calculate average sale value (AOV)
        $totalRevenue = $customers->sum(fn ($c) => $c->sales->sum('total_amount'));
        $totalSales = $customers->sum(fn ($c) => $c->sales->count());
        $averageSaleValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        // Calculate purchase frequency
        $averagePurchaseFrequency = $activeCustomers > 0 ? $totalSales / $activeCustomers : 0;

        // Calculate customer lifespan (in months)
        $averageCustomerLifespan = $this->calculateAverageCustomerLifespan($customers);

        // Calculate CLV using the formula: AOV × Purchase Frequency × Customer Lifespan
        $averageCLV = $averageSaleValue * $averagePurchaseFrequency * $averageCustomerLifespan;

        // Calculate CLV segments
        $clvSegments = $this->segmentCustomersByCLV($customers, $averageSaleValue, $averagePurchaseFrequency);

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'average_sale_value' => round($averageSaleValue, 2),
            'average_purchase_frequency' => round($averagePurchaseFrequency, 2),
            'average_customer_lifespan_months' => round($averageCustomerLifespan, 2),
            'average_clv' => round($averageCLV, 2),
            'total_customer_value' => round($totalRevenue, 2),
            'clv_segments' => $clvSegments,
            'clv_distribution' => $this->calculateCLVDistribution($customers, $averageSaleValue, $averagePurchaseFrequency),
        ];
    }

    /** Calculate Customer Acquisition Cost (CAC) metrics */
    private function calculateCACMetrics(Carbon $startDate, Carbon $endDate, string $period): array
    {
        // Get marketing spend data
        $marketingSpend = $this->getMarketingSpend($startDate, $endDate);

        // Get new customers acquired in the period
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();

        // Calculate CAC
        $totalCAC = $newCustomers > 0 ? $marketingSpend['total'] / $newCustomers : 0;

        // Calculate CAC by channel
        $cacByChannel = $this->calculateCACByChannel($startDate, $endDate);

        // Calculate CAC trends
        $cacTrends = $this->calculateCACTrends($startDate, $endDate, $period);

        return [
            'total_marketing_spend' => round($marketingSpend['total'], 2),
            'new_customers_acquired' => $newCustomers,
            'average_cac' => round($totalCAC, 2),
            'cac_by_channel' => $cacByChannel,
            'cac_trends' => $cacTrends,
            'marketing_spend_breakdown' => $marketingSpend['breakdown'],
            'cac_payback_period' => $this->calculateCACPaybackPeriod($totalCAC, $startDate, $endDate),
        ];
    }

    /** Calculate customer segment metrics */
    private function calculateSegmentMetrics(Carbon $startDate, Carbon $endDate, ?string $segmentBy): array
    {
        if (! $segmentBy) {
            return [];
        }

        $segments = [];

        switch ($segmentBy) {
            case 'acquisition_channel':
                $segments = $this->segmentByAcquisitionChannel($startDate, $endDate);

                break;
            case 'customer_type':
                $segments = $this->segmentByCustomerType($startDate, $endDate);

                break;
            case 'sale_frequency':
                $segments = $this->segmentBySaleFrequency($startDate, $endDate);

                break;
            case 'spending_level':
                $segments = $this->segmentBySpendingLevel($startDate, $endDate);

                break;
        }

        return $segments;
    }

    /** Calculate cohort analysis */
    private function calculateCohortAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        $cohorts = [];

        // Group customers by acquisition month
        $dateFormatSql = db_date_format('created_at', '%Y-%m');
        $customerCohorts = Customer::selectRaw("{$dateFormatSql} as cohort_month, COUNT(*) as customers")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('cohort_month')
            ->orderBy('cohort_month')
            ->get();

        foreach ($customerCohorts as $cohort) {
            $cohortStart = Carbon::createFromFormat('Y-m', $cohort->cohort_month)->startOfMonth();
            $cohortCustomers = Customer::whereMonth('created_at', $cohortStart->month)
                ->whereYear('created_at', $cohortStart->year)
                ->pluck('id');

            // Calculate retention rates for subsequent months
            $retentionRates = [];

            for ($i = 0; $i <= 12; $i++) {
                $periodStart = $cohortStart->copy()->addMonths($i);
                $periodEnd = $periodStart->copy()->endOfMonth();

                $activeCustomers = Sale::whereIn('customer_id', $cohortCustomers)
                    ->whereBetween('created_at', [$periodStart, $periodEnd])
                    ->where('status', 'completed')
                    ->distinct('customer_id')
                    ->count();

                $retentionRates[$i] = $cohort->customers > 0 ? round(($activeCustomers / $cohort->customers) * 100, 2) : 0;
            }

            $cohorts[] = [
                'cohort_month' => $cohort->cohort_month,
                'customers' => $cohort->customers,
                'retention_rates' => $retentionRates,
            ];
        }

        return $cohorts;
    }

    /** Calculate retention metrics */
    private function calculateRetentionMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $totalCustomers = Customer::where('created_at', '<', $startDate)->count();

        // Customers who made purchases in the period
        $activeCustomers = Customer::whereHas('sales', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        })->where('created_at', '<', $startDate)->count();

        $retentionRate = $totalCustomers > 0 ? ($activeCustomers / $totalCustomers) * 100 : 0;
        $churnRate = 100 - $retentionRate;

        // Calculate repeat purchase rate
        $customersWithMultipleSales = Customer::whereHas('sales', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        }, '>=', 2)->count();

        $customersWithSales = Customer::whereHas('sales', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        })->count();

        $repeatPurchaseRate = $customersWithSales > 0 ? ($customersWithMultipleSales / $customersWithSales) * 100 : 0;

        return [
            'retention_rate' => round($retentionRate, 2),
            'churn_rate' => round($churnRate, 2),
            'repeat_purchase_rate' => round($repeatPurchaseRate, 2),
            'total_existing_customers' => $totalCustomers,
            'active_existing_customers' => $activeCustomers,
            'customers_with_repeat_purchases' => $customersWithMultipleSales,
        ];
    }

    /** Calculate LTV:CAC ratio */
    private function calculateLTVCACRatio(Carbon $startDate, Carbon $endDate): array
    {
        $clvMetrics = $this->calculateCLVMetrics($startDate, $endDate, 'monthly');
        $cacMetrics = $this->calculateCACMetrics($startDate, $endDate, 'monthly');

        $ltvCacRatio = $cacMetrics['average_cac'] > 0 ? $clvMetrics['average_clv'] / $cacMetrics['average_cac'] : 0;

        // Determine ratio health
        $ratioHealth = 'poor';

        if ($ltvCacRatio >= 3) {
            $ratioHealth = 'excellent';
        } elseif ($ltvCacRatio >= 2) {
            $ratioHealth = 'good';
        } elseif ($ltvCacRatio >= 1) {
            $ratioHealth = 'acceptable';
        }

        return [
            'ltv_cac_ratio' => round($ltvCacRatio, 2),
            'ratio_health' => $ratioHealth,
            'average_clv' => $clvMetrics['average_clv'],
            'average_cac' => $cacMetrics['average_cac'],
            'recommendation' => $this->getLTVCACRecommendation($ltvCacRatio),
        ];
    }

    /** Calculate customer acquisition trends */
    private function calculateAcquisitionTrends(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $dateFormat = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            'quarterly' => '%Y-%q',
            default => '%Y-%m',
        };

        $dateFormatSql = db_date_format('created_at', $dateFormat);

        $trends = Customer::selectRaw("{$dateFormatSql} as period, COUNT(*) as new_customers")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => $item->period,
                    'new_customers' => $item->new_customers,
                ];
            })
            ->toArray();

        return $trends;
    }

    /** Calculate revenue per customer */
    private function calculateRevenuePerCustomer(Carbon $startDate, Carbon $endDate): array
    {
        $customerRevenue = Customer::with(['sales' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        }])->get()->map(function ($customer) {
            return [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'total_revenue' => $customer->sales->sum('total_amount'),
                'sale_count' => $customer->sales->count(),
                'average_sale_value' => $customer->sales->count() > 0 ? $customer->sales->sum('total_amount') / $customer->sales->count() : 0,
            ];
        })->sortByDesc('total_revenue');

        $totalRevenue = $customerRevenue->sum('total_revenue');
        $totalCustomers = $customerRevenue->where('total_revenue', '>', 0)->count();
        $averageRevenuePerCustomer = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_customers' => $totalCustomers,
            'average_revenue_per_customer' => round($averageRevenuePerCustomer, 2),
            'top_customers' => $customerRevenue->take(10)->values()->toArray(),
            'revenue_distribution' => $this->calculateRevenueDistribution($customerRevenue),
        ];
    }

    /** Calculate churn analysis */
    private function calculateChurnAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        // Define churn as customers who haven't made a purchase in the last 90 days
        $churnThreshold = Carbon::now()->subDays(90);

        $churnedCustomers = Customer::whereDoesntHave('sales', function ($query) use ($churnThreshold) {
            $query->where('created_at', '>=', $churnThreshold)
                ->where('status', 'completed');
        })->where('created_at', '<', $churnThreshold)->count();

        $totalCustomers = Customer::where('created_at', '<', $churnThreshold)->count();
        $churnRate = $totalCustomers > 0 ? ($churnedCustomers / $totalCustomers) * 100 : 0;

        // Calculate churn reasons (if available)
        $churnReasons = $this->analyzeChurnReasons($startDate, $endDate);

        return [
            'churned_customers' => $churnedCustomers,
            'total_customers' => $totalCustomers,
            'churn_rate' => round($churnRate, 2),
            'churn_threshold_days' => 90,
            'churn_reasons' => $churnReasons,
            'at_risk_customers' => $this->identifyAtRiskCustomers(),
        ];
    }

    /** Calculate customer value distribution */
    private function calculateValueDistribution(Carbon $startDate, Carbon $endDate): array
    {
        $customerValues = Customer::with(['sales' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed');
        }])->get()->map(function ($customer) {
            return $customer->sales->sum('total_amount');
        })->filter(fn ($value) => $value > 0)->sort();

        $total = $customerValues->count();

        if ($total === 0) {
            return [
                'low_value' => 0,
                'medium_value' => 0,
                'high_value' => 0,
                'percentiles' => [],
            ];
        }

        // Calculate percentiles
        $percentiles = [
            'p25' => $customerValues->values()[intval($total * 0.25)] ?? 0,
            'p50' => $customerValues->values()[intval($total * 0.50)] ?? 0,
            'p75' => $customerValues->values()[intval($total * 0.75)] ?? 0,
            'p90' => $customerValues->values()[intval($total * 0.90)] ?? 0,
        ];

        // Segment customers by value
        $lowValue = $customerValues->filter(fn ($v) => $v <= $percentiles['p25'])->count();
        $mediumValue = $customerValues->filter(fn ($v) => $v > $percentiles['p25'] && $v <= $percentiles['p75'])->count();
        $highValue = $customerValues->filter(fn ($v) => $v > $percentiles['p75'])->count();

        return [
            'low_value' => $lowValue,
            'medium_value' => $mediumValue,
            'high_value' => $highValue,
            'percentiles' => $percentiles,
            'total_customers' => $total,
        ];
    }

    // Helper methods

    private function calculateAverageCustomerLifespan(Collection $customers): float
    {
        $lifespans = $customers->map(function ($customer) {
            if ($customer->sales->isEmpty()) {
                return 0;
            }

            $firstSale = $customer->sales->min('created_at');
            $lastSale = $customer->sales->max('created_at');

            return Carbon::parse($firstSale)->diffInMonths(Carbon::parse($lastSale)) + 1;
        })->filter(fn ($lifespan) => $lifespan > 0);

        return $lifespans->isEmpty() ? 1 : $lifespans->average();
    }

    private function segmentCustomersByCLV(Collection $customers, float $aov, float $frequency): array
    {
        $segments = ['high' => 0, 'medium' => 0, 'low' => 0];

        foreach ($customers as $customer) {
            $customerSales = $customer->sales->count();
            $customerRevenue = $customer->sales->sum('total_amount');
            $customerCLV = $customerSales > 0 ? ($customerRevenue / $customerSales) * $frequency * 12 : 0;

            if ($customerCLV > $aov * $frequency * 12 * 1.5) {
                $segments['high']++;
            } elseif ($customerCLV > $aov * $frequency * 12 * 0.8) {
                $segments['medium']++;
            } else {
                $segments['low']++;
            }
        }

        return $segments;
    }

    private function calculateCLVDistribution(Collection $customers, float $aov, float $frequency): array
    {
        $clvValues = $customers->map(function ($customer) use ($frequency) {
            $customerSales = $customer->sales->count();
            $customerRevenue = $customer->sales->sum('total_amount');

            return $customerSales > 0 ? ($customerRevenue / $customerSales) * $frequency * 12 : 0;
        })->filter(fn ($clv) => $clv > 0)->sort();

        $total = $clvValues->count();

        if ($total === 0) {
            return ['min' => 0, 'max' => 0, 'median' => 0, 'average' => 0];
        }

        return [
            'min' => round($clvValues->min(), 2),
            'max' => round($clvValues->max(), 2),
            'median' => round($clvValues->values()[intval($total * 0.5)] ?? 0, 2),
            'average' => round($clvValues->average(), 2),
        ];
    }

    private function getMarketingSpend(Carbon $startDate, Carbon $endDate): array
    {
        // This would typically come from a marketing_campaigns or marketing_spend table
        // For now, we'll use a placeholder calculation
        $totalSpend = 10000; // Placeholder

        return [
            'total' => $totalSpend,
            'breakdown' => [
                'digital_advertising' => $totalSpend * 0.4,
                'social_media' => $totalSpend * 0.3,
                'email_marketing' => $totalSpend * 0.1,
                'content_marketing' => $totalSpend * 0.1,
                'other' => $totalSpend * 0.1,
            ],
        ];
    }

    private function calculateCACByChannel(Carbon $startDate, Carbon $endDate): array
    {
        // Placeholder implementation - would need actual channel tracking
        return [
            'organic' => ['customers' => 50, 'spend' => 0, 'cac' => 0],
            'paid_search' => ['customers' => 30, 'spend' => 3000, 'cac' => 100],
            'social_media' => ['customers' => 25, 'spend' => 2500, 'cac' => 100],
            'email' => ['customers' => 15, 'spend' => 500, 'cac' => 33.33],
            'referral' => ['customers' => 10, 'spend' => 200, 'cac' => 20],
        ];
    }

    private function calculateCACTrends(Carbon $startDate, Carbon $endDate, string $period): array
    {
        // Placeholder implementation
        return [];
    }

    private function calculateCACPaybackPeriod(float $cac, Carbon $startDate, Carbon $endDate): array
    {
        // Calculate average monthly revenue per customer
        $monthlyRevenuePerCustomer = 150; // Placeholder
        $paybackMonths = $monthlyRevenuePerCustomer > 0 ? $cac / $monthlyRevenuePerCustomer : 0;

        return [
            'payback_period_months' => round($paybackMonths, 1),
            'monthly_revenue_per_customer' => $monthlyRevenuePerCustomer,
        ];
    }

    private function segmentByAcquisitionChannel(Carbon $startDate, Carbon $endDate): array
    {
        // Placeholder implementation
        return [];
    }

    private function segmentByCustomerType(Carbon $startDate, Carbon $endDate): array
    {
        // Placeholder implementation
        return [];
    }

    private function segmentBySaleFrequency(Carbon $startDate, Carbon $endDate): array
    {
        // Placeholder implementation
        return [];
    }

    private function segmentBySpendingLevel(Carbon $startDate, Carbon $endDate): array
    {
        // Placeholder implementation
        return [];
    }

    private function calculateRevenueDistribution(Collection $customerRevenue): array
    {
        $revenues = $customerRevenue->pluck('total_revenue')->filter(fn ($r) => $r > 0)->sort();
        $total = $revenues->count();

        if ($total === 0) {
            return ['top_20_percent' => 0, 'middle_60_percent' => 0, 'bottom_20_percent' => 0];
        }

        return [
            'top_20_percent' => $revenues->slice(intval($total * 0.8))->sum(),
            'middle_60_percent' => $revenues->slice(intval($total * 0.2), intval($total * 0.6))->sum(),
            'bottom_20_percent' => $revenues->slice(0, intval($total * 0.2))->sum(),
        ];
    }

    private function analyzeChurnReasons(Carbon $startDate, Carbon $endDate): array
    {
        // Placeholder implementation - would analyze sale patterns, feedback, etc.
        return [
            'price_sensitivity' => 25,
            'service_quality' => 20,
            'competition' => 30,
            'location_change' => 15,
            'other' => 10,
        ];
    }

    private function identifyAtRiskCustomers(): array
    {
        // Customers who haven't saleed in 30-60 days
        $atRiskThreshold = Carbon::now()->subDays(30);
        $churnThreshold = Carbon::now()->subDays(60);

        $atRiskCustomers = Customer::whereHas('sales', function ($query) use ($churnThreshold) {
            $query->where('created_at', '>=', $churnThreshold)
                ->where('status', 'completed');
        })->whereDoesntHave('sales', function ($query) use ($atRiskThreshold) {
            $query->where('created_at', '>=', $atRiskThreshold)
                ->where('status', 'completed');
        })->count();

        return [
            'count' => $atRiskCustomers,
            'threshold_days' => 30,
        ];
    }

    private function getLTVCACRecommendation(float $ratio): string
    {
        if ($ratio >= 3) {
            return 'Excellent ratio! Consider increasing marketing spend to acquire more customers.';
        } elseif ($ratio >= 2) {
            return 'Good ratio. Monitor closely and optimize acquisition channels.';
        } elseif ($ratio >= 1) {
            return 'Acceptable but needs improvement. Focus on increasing CLV or reducing CAC.';
        } else {
            return 'Poor ratio. Urgent action needed to improve profitability of customer acquisition.';
        }
    }
}
