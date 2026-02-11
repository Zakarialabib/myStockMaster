<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\CashRegister;
use Carbon\Carbon;

class GenerateFinancialReportsAction
{
    public function __invoke(array $params = []): array
    {
        $period = $params['period'] ?? 'monthly';
        $startDate = $params['start_date'] ?? null;
        $endDate = $params['end_date'] ?? null;
        $reportType = $params['report_type'] ?? 'profit_loss';

        // Set default date range based on period
        if ( ! $startDate || ! $endDate) {
            [$startDate, $endDate] = $this->getDateRange($period);
        }

        return match ($reportType) {
            'profit_loss'      => $this->generateProfitLossStatement($startDate, $endDate, $period),
            'cash_flow'        => $this->generateCashFlowStatement($startDate, $endDate, $period),
            'balance_sheet'    => $this->generateBalanceSheet($startDate, $endDate),
            'income_statement' => $this->generateIncomeStatement($startDate, $endDate, $period),
            'expense_report'   => $this->generateExpenseReport($startDate, $endDate, $period),
            'revenue_analysis' => $this->generateRevenueAnalysis($startDate, $endDate, $period),
            'comparative'      => $this->generateComparativeReport($startDate, $endDate, $period),
            default            => $this->generateProfitLossStatement($startDate, $endDate, $period)
        };
    }

    private function generateProfitLossStatement(Carbon $startDate, Carbon $endDate, string $period): array
    {
        // Revenue calculations
        $totalRevenue = $this->calculateTotalRevenue($startDate, $endDate);
        $revenueByCategory = $this->getRevenueByCategory($startDate, $endDate);
        $revenueByPeriod = $this->getRevenueByPeriod($startDate, $endDate, $period);

        // Cost of Goods Sold (COGS)
        $cogs = $this->calculateCOGS($startDate, $endDate);
        $grossProfit = $totalRevenue - $cogs;
        $grossMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // Operating Expenses
        $operatingExpenses = $this->calculateOperatingExpenses($startDate, $endDate);
        $operatingIncome = $grossProfit - $operatingExpenses['total'];
        $operatingMargin = $totalRevenue > 0 ? ($operatingIncome / $totalRevenue) * 100 : 0;

        // Other Income/Expenses
        $otherIncome = $this->calculateOtherIncome($startDate, $endDate);
        $otherExpenses = $this->calculateOtherExpenses($startDate, $endDate);

        // Net Income
        $netIncome = $operatingIncome + $otherIncome - $otherExpenses;
        $netMargin = $totalRevenue > 0 ? ($netIncome / $totalRevenue) * 100 : 0;

        // Previous period comparison
        $previousPeriod = $this->getPreviousPeriodComparison($startDate, $endDate, $period);

        return [
            'report_type' => 'profit_loss',
            'period'      => $period,
            'start_date'  => $startDate->format('Y-m-d'),
            'end_date'    => $endDate->format('Y-m-d'),
            'currency'    => 'USD',
            'revenue'     => [
                'total'       => $totalRevenue,
                'by_category' => $revenueByCategory,
                'by_period'   => $revenueByPeriod,
                'growth_rate' => $previousPeriod['revenue_growth'] ?? 0,
            ],
            'cost_of_goods_sold' => [
                'total'                 => $cogs,
                'percentage_of_revenue' => $totalRevenue > 0 ? ($cogs / $totalRevenue) * 100 : 0,
                'breakdown'             => $this->getCOGSBreakdown($startDate, $endDate),
            ],
            'gross_profit' => [
                'amount' => $grossProfit,
                'margin' => $grossMargin,
                'trend'  => $this->calculateTrend($grossProfit, $previousPeriod['gross_profit'] ?? 0),
            ],
            'operating_expenses' => array_merge($operatingExpenses, [
                'percentage_of_revenue' => $totalRevenue > 0 ? ($operatingExpenses['total'] / $totalRevenue) * 100 : 0,
            ]),
            'operating_income' => [
                'amount' => $operatingIncome,
                'margin' => $operatingMargin,
                'trend'  => $this->calculateTrend($operatingIncome, $previousPeriod['operating_income'] ?? 0),
            ],
            'other_income'   => $otherIncome,
            'other_expenses' => $otherExpenses,
            'net_income'     => [
                'amount'  => $netIncome,
                'margin'  => $netMargin,
                'trend'   => $this->calculateTrend($netIncome, $previousPeriod['net_income'] ?? 0),
                'per_day' => $netIncome / max(1, $startDate->diffInDays($endDate)),
            ],
            'key_ratios' => [
                'gross_margin'     => $grossMargin,
                'operating_margin' => $operatingMargin,
                'net_margin'       => $netMargin,
                'expense_ratio'    => $totalRevenue > 0 ? (($operatingExpenses['total'] + $otherExpenses) / $totalRevenue) * 100 : 0,
            ],
            'previous_period_comparison' => $previousPeriod,
            'generated_at'               => now()->toISOString(),
        ];
    }

    private function generateCashFlowStatement(Carbon $startDate, Carbon $endDate, string $period): array
    {
        // Operating Cash Flow
        $operatingCashFlow = $this->calculateOperatingCashFlow($startDate, $endDate);

        // Investing Cash Flow
        $investingCashFlow = $this->calculateInvestingCashFlow($startDate, $endDate);

        // Financing Cash Flow
        $financingCashFlow = $this->calculateFinancingCashFlow($startDate, $endDate);

        // Net Cash Flow
        $netCashFlow = $operatingCashFlow['total'] + $investingCashFlow['total'] + $financingCashFlow['total'];

        // Cash positions
        $beginningCash = $this->getBeginningCashPosition($startDate);
        $endingCash = $beginningCash + $netCashFlow;

        return [
            'report_type'           => 'cash_flow',
            'period'                => $period,
            'start_date'            => $startDate->format('Y-m-d'),
            'end_date'              => $endDate->format('Y-m-d'),
            'currency'              => 'USD',
            'beginning_cash'        => $beginningCash,
            'operating_activities'  => $operatingCashFlow,
            'investing_activities'  => $investingCashFlow,
            'financing_activities'  => $financingCashFlow,
            'net_cash_flow'         => $netCashFlow,
            'ending_cash'           => $endingCash,
            'cash_flow_by_period'   => $this->getCashFlowByPeriod($startDate, $endDate, $period),
            'cash_conversion_cycle' => $this->calculateCashConversionCycle($startDate, $endDate),
            'generated_at'          => now()->toISOString(),
        ];
    }

    private function generateBalanceSheet(Carbon $startDate, Carbon $endDate): array
    {
        // Assets
        $currentAssets = $this->calculateCurrentAssets($endDate);
        $fixedAssets = $this->calculateFixedAssets($endDate);
        $totalAssets = $currentAssets['total'] + $fixedAssets['total'];

        // Liabilities
        $currentLiabilities = $this->calculateCurrentLiabilities($endDate);
        $longTermLiabilities = $this->calculateLongTermLiabilities($endDate);
        $totalLiabilities = $currentLiabilities['total'] + $longTermLiabilities['total'];

        // Equity
        $equity = $this->calculateEquity($endDate);

        return [
            'report_type' => 'balance_sheet',
            'as_of_date'  => $endDate->format('Y-m-d'),
            'currency'    => 'USD',
            'assets'      => [
                'current_assets' => $currentAssets,
                'fixed_assets'   => $fixedAssets,
                'total_assets'   => $totalAssets,
            ],
            'liabilities' => [
                'current_liabilities'   => $currentLiabilities,
                'long_term_liabilities' => $longTermLiabilities,
                'total_liabilities'     => $totalLiabilities,
            ],
            'equity'                       => $equity,
            'total_liabilities_and_equity' => $totalLiabilities + $equity['total'],
            'financial_ratios'             => [
                'current_ratio'  => $currentLiabilities['total'] > 0 ? $currentAssets['total'] / $currentLiabilities['total'] : 0,
                'debt_to_equity' => $equity['total'] > 0 ? $totalLiabilities / $equity['total'] : 0,
                'asset_turnover' => $this->calculateAssetTurnover($startDate, $endDate, $totalAssets),
            ],
            'generated_at' => now()->toISOString(),
        ];
    }

    private function generateIncomeStatement(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $revenue = $this->calculateTotalRevenue($startDate, $endDate);
        $expenses = $this->calculateTotalExpenses($startDate, $endDate);
        $netIncome = $revenue - $expenses;

        return [
            'report_type' => 'income_statement',
            'period'      => $period,
            'start_date'  => $startDate->format('Y-m-d'),
            'end_date'    => $endDate->format('Y-m-d'),
            'currency'    => 'USD',
            'revenue'     => [
                'total'     => $revenue,
                'breakdown' => $this->getRevenueBreakdown($startDate, $endDate),
            ],
            'expenses' => [
                'total'     => $expenses,
                'breakdown' => $this->getExpenseBreakdown($startDate, $endDate),
            ],
            'net_income'       => $netIncome,
            'earnings_per_day' => $netIncome / max(1, $startDate->diffInDays($endDate)),
            'generated_at'     => now()->toISOString(),
        ];
    }

    private function generateExpenseReport(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();

        $expensesByCategory = $expenses->groupBy('category')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc();

        $expensesByMonth = $expenses->groupBy(fn ($expense) => $expense->date->format('Y-m'))
            ->map(fn ($group) => $group->sum('amount'));

        $topExpenses = $expenses->sortByDesc('amount')->take(10);

        return [
            'report_type'          => 'expense_report',
            'period'               => $period,
            'start_date'           => $startDate->format('Y-m-d'),
            'end_date'             => $endDate->format('Y-m-d'),
            'currency'             => 'USD',
            'total_expenses'       => $expenses->sum('amount'),
            'expense_count'        => $expenses->count(),
            'average_expense'      => $expenses->count() > 0 ? $expenses->sum('amount') / $expenses->count() : 0,
            'expenses_by_category' => $expensesByCategory->toArray(),
            'expenses_by_period'   => $expensesByMonth->toArray(),
            'top_expenses'         => $topExpenses->map(fn ($expense) => [
                'description' => $expense->description,
                'amount'      => $expense->amount,
                'category'    => $expense->category,
                'date'        => $expense->date->format('Y-m-d'),
            ])->toArray(),
            'expense_trends' => $this->calculateExpenseTrends($startDate, $endDate, $period),
            'generated_at'   => now()->toISOString(),
        ];
    }

    private function generateRevenueAnalysis(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $sales = Sale::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $orderCount = $sales->count();
        $averageSaleValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        return [
            'report_type'               => 'revenue_analysis',
            'period'                    => $period,
            'start_date'                => $startDate->format('Y-m-d'),
            'end_date'                  => $endDate->format('Y-m-d'),
            'currency'                  => 'USD',
            'total_revenue'             => $totalRevenue,
            'order_count'               => $orderCount,
            'average_order_value'       => $averageSaleValue,
            'revenue_by_day'            => $this->getRevenueByDay($startDate, $endDate),
            'revenue_by_hour'           => $this->getRevenueByHour($startDate, $endDate),
            'revenue_by_payment_method' => $this->getRevenueByPaymentMethod($startDate, $endDate),
            'top_selling_items'         => $this->getTopSellingItems($startDate, $endDate),
            'revenue_trends'            => $this->calculateRevenueTrends($startDate, $endDate, $period),
            'generated_at'              => now()->toISOString(),
        ];
    }

    private function generateComparativeReport(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $currentPeriod = $this->generateProfitLossStatement($startDate, $endDate, $period);

        // Calculate previous period dates
        $periodLength = $startDate->diffInDays($endDate);
        $previousEndDate = $startDate->copy()->subDay();
        $previousStartDate = $previousEndDate->copy()->subDays($periodLength);

        $previousPeriod = $this->generateProfitLossStatement($previousStartDate, $previousEndDate, $period);

        return [
            'report_type'       => 'comparative',
            'period'            => $period,
            'current_period'    => $currentPeriod,
            'previous_period'   => $previousPeriod,
            'variance_analysis' => $this->calculateVarianceAnalysis($currentPeriod, $previousPeriod),
            'generated_at'      => now()->toISOString(),
        ];
    }

    private function getDateRange(string $period): array
    {
        $endDate = now();

        $startDate = match ($period) {
            'daily'     => $endDate->copy()->startOfDay(),
            'weekly'    => $endDate->copy()->startOfWeek(),
            'monthly'   => $endDate->copy()->startOfMonth(),
            'quarterly' => $endDate->copy()->startOfQuarter(),
            'yearly'    => $endDate->copy()->startOfYear(),
            default     => $endDate->copy()->startOfMonth()
        };

        return [$startDate, $endDate];
    }

    private function calculateTotalRevenue(Carbon $startDate, Carbon $endDate): float
    {
        return Sale::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    private function calculateCOGS(Carbon $startDate, Carbon $endDate): float
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['beverages', 'inventory', 'supplies'])
            ->sum('amount');
    }

    private function calculateOperatingExpenses(Carbon $startDate, Carbon $endDate): array
    {
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['rent', 'utilities', 'marketing', 'maintenance', 'insurance', 'licenses'])
            ->get();

        $breakdown = $expenses->groupBy('category')
            ->map(fn ($group) => $group->sum('amount'));

        return [
            'total'     => $expenses->sum('amount'),
            'breakdown' => $breakdown->toArray(),
        ];
    }

    private function calculateOtherIncome(Carbon $startDate, Carbon $endDate): float
    {
        // This could include interest income, investment gains, etc.
        // For now, we'll return 0 as this would need additional models
        return 0;
    }

    private function calculateOtherExpenses(Carbon $startDate, Carbon $endDate): float
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['interest', 'taxes', 'depreciation', 'other'])
            ->sum('amount');
    }

    private function getRevenueByCategory(Carbon $startDate, Carbon $endDate): array
    {
        // This would require sale items with categories
        // For now, return basic structure
        return [
            'food'      => $this->calculateTotalRevenue($startDate, $endDate) * 0.7,
            'beverages' => $this->calculateTotalRevenue($startDate, $endDate) * 0.3,
        ];
    }

    private function getRevenueByPeriod(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $format = match ($period) {
            'daily'     => 'Y-m-d H:00',
            'weekly'    => 'Y-m-d',
            'monthly'   => 'Y-m-d',
            'quarterly' => 'Y-m',
            'yearly'    => 'Y-m',
            default     => 'Y-m-d'
        };

        return Sale::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn ($sale) => $sale->date->format($format))
            ->map(fn ($group) => $group->sum('total_amount'))
            ->toArray();
    }

    private function getPreviousPeriodComparison(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $periodLength = $startDate->diffInDays($endDate);
        $previousEndDate = $startDate->copy()->subDay();
        $previousStartDate = $previousEndDate->copy()->subDays($periodLength);

        $previousRevenue = $this->calculateTotalRevenue($previousStartDate, $previousEndDate);
        $previousCOGS = $this->calculateCOGS($previousStartDate, $previousEndDate);
        $previousGrossProfit = $previousRevenue - $previousCOGS;
        $previousOperatingExpenses = $this->calculateOperatingExpenses($previousStartDate, $previousEndDate);
        $previousOperatingIncome = $previousGrossProfit - $previousOperatingExpenses['total'];
        $previousOtherExpenses = $this->calculateOtherExpenses($previousStartDate, $previousEndDate);
        $previousNetIncome = $previousOperatingIncome - $previousOtherExpenses;

        $currentRevenue = $this->calculateTotalRevenue($startDate, $endDate);

        return [
            'revenue'          => $previousRevenue,
            'revenue_growth'   => $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0,
            'gross_profit'     => $previousGrossProfit,
            'operating_income' => $previousOperatingIncome,
            'net_income'       => $previousNetIncome,
        ];
    }

    private function calculateTrend(float $current, float $previous): string
    {
        if ($previous == 0) {
            return $current > 0 ? 'up' : 'flat';
        }

        $change = (($current - $previous) / $previous) * 100;

        if ($change > 5) {
            return 'up';
        }

        if ($change < -5) {
            return 'down';
        }

        return 'flat';
    }

    private function calculateOperatingCashFlow(Carbon $startDate, Carbon $endDate): array
    {
        $netIncome = $this->calculateTotalRevenue($startDate, $endDate) - $this->calculateTotalExpenses($startDate, $endDate);

        // Simplified operating cash flow calculation
        return [
            'total'                   => $netIncome,
            'net_income'              => $netIncome,
            'depreciation'            => 0, // Would need asset tracking
            'working_capital_changes' => 0, // Would need detailed balance sheet tracking
        ];
    }

    private function calculateInvestingCashFlow(Carbon $startDate, Carbon $endDate): array
    {
        $equipmentPurchases = Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['equipment', 'furniture', 'technology'])
            ->sum('amount');

        return [
            'total'               => -$equipmentPurchases,
            'equipment_purchases' => -$equipmentPurchases,
            'asset_sales'         => 0,
        ];
    }

    private function calculateFinancingCashFlow(Carbon $startDate, Carbon $endDate): array
    {
        // This would require loan and investment tracking
        return [
            'total'             => 0,
            'loan_proceeds'     => 0,
            'loan_payments'     => 0,
            'owner_investments' => 0,
            'owner_withdrawals' => 0,
        ];
    }

    private function getBeginningCashPosition(Carbon $startDate): float
    {
        return CashRegister::where('date', '<', $startDate)
            ->sum('closing_balance') ?? 0;
    }

    private function getCashFlowByPeriod(Carbon $startDate, Carbon $endDate, string $period): array
    {
        // Simplified cash flow by period
        return [];
    }

    private function calculateCashConversionCycle(Carbon $startDate, Carbon $endDate): array
    {
        // Simplified cash conversion cycle
        return [
            'days_sales_outstanding'     => 0, // Restaurants typically have immediate payment
            'days_inventory_outstanding' => 7, // Typical food inventory turnover
            'days_payable_outstanding'   => 30, // Typical supplier payment terms
            'cash_conversion_cycle'      => -23, // 0 + 7 - 30
        ];
    }

    private function calculateCurrentAssets(Carbon $asOfDate): array
    {
        $cash = CashRegister::where('date', '<=', $asOfDate)
            ->sum('closing_balance');

        return [
            'total'               => $cash,
            'cash'                => $cash,
            'accounts_receivable' => 0, // Restaurants typically don't have AR
            'inventory'           => 0, // Would need inventory tracking
        ];
    }

    private function calculateFixedAssets(Carbon $asOfDate): array
    {
        // This would require asset tracking
        return [
            'total'                    => 0,
            'equipment'                => 0,
            'furniture'                => 0,
            'accumulated_depreciation' => 0,
        ];
    }

    private function calculateCurrentLiabilities(Carbon $asOfDate): array
    {
        // This would require liability tracking
        return [
            'total'            => 0,
            'accounts_payable' => 0,
            'accrued_expenses' => 0,
            'short_term_debt'  => 0,
        ];
    }

    private function calculateLongTermLiabilities(Carbon $asOfDate): array
    {
        return [
            'total'           => 0,
            'long_term_debt'  => 0,
            'equipment_loans' => 0,
        ];
    }

    private function calculateEquity(Carbon $asOfDate): array
    {
        // Simplified equity calculation
        $totalAssets = $this->calculateCurrentAssets($asOfDate)['total'] + $this->calculateFixedAssets($asOfDate)['total'];
        $totalLiabilities = $this->calculateCurrentLiabilities($asOfDate)['total'] + $this->calculateLongTermLiabilities($asOfDate)['total'];

        return [
            'total'             => $totalAssets - $totalLiabilities,
            'owner_equity'      => $totalAssets - $totalLiabilities,
            'retained_earnings' => 0,
        ];
    }

    private function calculateAssetTurnover(Carbon $startDate, Carbon $endDate, float $totalAssets): float
    {
        $revenue = $this->calculateTotalRevenue($startDate, $endDate);

        return $totalAssets > 0 ? $revenue / $totalAssets : 0;
    }

    private function calculateTotalExpenses(Carbon $startDate, Carbon $endDate): float
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }

    private function getRevenueBreakdown(Carbon $startDate, Carbon $endDate): array
    {
        return $this->getRevenueByCategory($startDate, $endDate);
    }

    private function getExpenseBreakdown(Carbon $startDate, Carbon $endDate): array
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy('category')
            ->map(fn ($group) => $group->sum('amount'))
            ->toArray();
    }

    private function getCOGSBreakdown(Carbon $startDate, Carbon $endDate): array
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->whereIn('category', ['beverages', 'inventory', 'supplies'])
            ->get()
            ->groupBy('category')
            ->map(fn ($group) => $group->sum('amount'))
            ->toArray();
    }

    private function getRevenueByDay(Carbon $startDate, Carbon $endDate): array
    {
        return Sale::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn ($sale) => $sale->date->format('Y-m-d'))
            ->map(fn ($group) => $group->sum('total_amount'))
            ->toArray();
    }

    private function getRevenueByHour(Carbon $startDate, Carbon $endDate): array
    {
        return Sale::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn ($sale) => $sale->date->format('H:00'))
            ->map(fn ($group) => $group->sum('total_amount'))
            ->toArray();
    }

    private function getRevenueByPaymentMethod(Carbon $startDate, Carbon $endDate): array
    {
        return Sale::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get()
            ->groupBy('payment_method')
            ->map(fn ($group) => $group->sum('total_amount'))
            ->toArray();
    }

    private function getTopSellingItems(Carbon $startDate, Carbon $endDate): array
    {
        // This would require sale items tracking
        return [];
    }

    private function calculateRevenueTrends(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $revenueByPeriod = $this->getRevenueByPeriod($startDate, $endDate, $period);

        return [
            'trend_direction'   => 'up', // Simplified
            'growth_rate'       => 0,
            'seasonal_patterns' => [],
        ];
    }

    private function calculateExpenseTrends(Carbon $startDate, Carbon $endDate, string $period): array
    {
        return [
            'trend_direction'         => 'stable',
            'growth_rate'             => 0,
            'cost_control_efficiency' => 85,
        ];
    }

    private function calculateVarianceAnalysis(array $current, array $previous): array
    {
        $revenueVariance = $current['revenue']['total'] - $previous['revenue']['total'];
        $expenseVariance = ($current['operating_expenses']['total'] + $current['other_expenses']) -
            ($previous['operating_expenses']['total'] + $previous['other_expenses']);

        return [
            'revenue_variance' => [
                'amount'     => $revenueVariance,
                'percentage' => $previous['revenue']['total'] > 0 ? ($revenueVariance / $previous['revenue']['total']) * 100 : 0,
            ],
            'expense_variance' => [
                'amount'     => $expenseVariance,
                'percentage' => ($previous['operating_expenses']['total'] + $previous['other_expenses']) > 0 ?
                    ($expenseVariance / ($previous['operating_expenses']['total'] + $previous['other_expenses'])) * 100 : 0,
            ],
            'net_income_variance' => [
                'amount'     => $current['net_income']['amount'] - $previous['net_income']['amount'],
                'percentage' => $previous['net_income']['amount'] > 0 ?
                    (($current['net_income']['amount'] - $previous['net_income']['amount']) / $previous['net_income']['amount']) * 100 : 0,
            ],
        ];
    }
}
