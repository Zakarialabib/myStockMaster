<?php

declare(strict_types=1);

namespace App\Actions\Finance;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\CashFlowRecord;
use Carbon\Carbon;

class ManageFinancialWorkflowAction
{
    public function __invoke(string $period = 'daily'): array
    {
        return match ($period) {
            'daily'     => $this->getDailyTasks(),
            'weekly'    => $this->getWeeklyTasks(),
            'monthly'   => $this->getMonthlyTasks(),
            'quarterly' => $this->getQuarterlyTasks(),
            default     => $this->getDailyTasks(),
        };
    }

    private function getDailyTasks(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Get yesterday's financial data
        $yesterdayRevenue = Sale::whereDate('created_at', $yesterday)
            ->where('status', 'completed')
            ->sum('total_amount');

        $yesterdayExpenses = Expense::whereDate('date', $yesterday)
            ->sum('amount');

        $cashFlowRecord = CashFlowRecord::whereDate('record_date', $yesterday)->first();

        $tasks = [
            'period'          => 'daily',
            'date'            => $today->format('Y-m-d'),
            'completed_tasks' => [],
            'pending_tasks'   => [],
            'overdue_tasks'   => [],
            'metrics'         => [
                'yesterday_revenue'  => $yesterdayRevenue,
                'yesterday_expenses' => $yesterdayExpenses,
                'net_cash_flow'      => $yesterdayRevenue - $yesterdayExpenses,
                'cash_flow_recorded' => $cashFlowRecord !== null,
            ],
            'reminders' => [],
            'alerts'    => [],
        ];

        // Daily task checklist
        $dailyChecklist = [
            [
                'id'             => 'record_cash_flow',
                'title'          => 'Record Daily Cash Flow',
                'description'    => 'Update cash position and record daily transactions',
                'priority'       => 'high',
                'estimated_time' => 5,
                'category'       => 'cash_management',
            ],
            [
                'id'             => 'review_expenses',
                'title'          => 'Review Daily Expenses',
                'description'    => 'Verify and categorize all expenses from yesterday',
                'priority'       => 'medium',
                'estimated_time' => 10,
                'category'       => 'expense_management',
            ],
            [
                'id'             => 'check_inventory_costs',
                'title'          => 'Check Inventory Costs',
                'description'    => 'Review food costs and wastage from yesterday',
                'priority'       => 'medium',
                'estimated_time' => 15,
                'category'       => 'cost_control',
            ],
            [
                'id'             => 'update_sales_data',
                'title'          => 'Update Sales Data',
                'description'    => 'Ensure all sales transactions are properly recorded',
                'priority'       => 'high',
                'estimated_time' => 5,
                'category'       => 'revenue_tracking',
            ],
        ];

        // Categorize tasks based on completion status
        foreach ($dailyChecklist as $task) {
            $isCompleted = $this->isTaskCompleted($task['id'], 'daily', $today);
            $isOverdue = $this->isTaskOverdue($task['id'], 'daily', $today);

            if ($isCompleted) {
                $tasks['completed_tasks'][] = $task;
            } elseif ($isOverdue) {
                $tasks['overdue_tasks'][] = $task;
            } else {
                $tasks['pending_tasks'][] = $task;
            }
        }

        // Generate reminders
        if ( ! $cashFlowRecord) {
            $tasks['reminders'][] = [
                'type'     => 'missing_data',
                'title'    => 'Cash Flow Not Recorded',
                'message'  => 'Yesterday\'s cash flow has not been recorded yet.',
                'action'   => 'record_cash_flow',
                'priority' => 'high',
            ];
        }

        if ($yesterdayExpenses > $yesterdayRevenue) {
            $tasks['alerts'][] = [
                'type'     => 'negative_cash_flow',
                'title'    => 'Negative Cash Flow Alert',
                'message'  => 'Yesterday\'s expenses exceeded revenue by $'.number_format($yesterdayExpenses - $yesterdayRevenue, 2),
                'priority' => 'critical',
            ];
        }

        return $tasks;
    }

    private function getWeeklyTasks(): array
    {
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();

        // Get last week's financial data
        $weeklyRevenue = Sale::whereBetween('created_at', [
            $lastWeek,
            $lastWeek->copy()->endOfWeek(),
        ])->where('status', 'completed')->sum('total_amount');

        $weeklyExpenses = Expense::whereBetween('date', [
            $lastWeek->format('Y-m-d'),
            $lastWeek->copy()->endOfWeek()->format('Y-m-d'),
        ])->sum('amount');

        $tasks = [
            'period'          => 'weekly',
            'week_start'      => $thisWeek->format('Y-m-d'),
            'completed_tasks' => [],
            'pending_tasks'   => [],
            'overdue_tasks'   => [],
            'metrics'         => [
                'last_week_revenue'  => $weeklyRevenue,
                'last_week_expenses' => $weeklyExpenses,
                'weekly_profit'      => $weeklyRevenue - $weeklyExpenses,
                'profit_margin'      => $weeklyRevenue > 0 ? (($weeklyRevenue - $weeklyExpenses) / $weeklyRevenue) * 100 : 0,
            ],
            'reminders' => [],
            'alerts'    => [],
        ];

        $weeklyChecklist = [
            [
                'id'             => 'analyze_weekly_performance',
                'title'          => 'Analyze Weekly Performance',
                'description'    => 'Review KPIs, profit margins, and cost ratios',
                'priority'       => 'high',
                'estimated_time' => 30,
                'category'       => 'performance_analysis',
            ],
            [
                'id'             => 'reconcile_accounts',
                'title'          => 'Reconcile Bank Accounts',
                'description'    => 'Match bank statements with recorded transactions',
                'priority'       => 'high',
                'estimated_time' => 45,
                'category'       => 'accounting',
            ],
            [
                'id'             => 'review_supplier_payments',
                'title'          => 'Review Supplier Payments',
                'description'    => 'Check outstanding invoices and payment schedules',
                'priority'       => 'medium',
                'estimated_time' => 20,
                'category'       => 'payables',
            ],
            [
                'id'             => 'staff_cost_analysis',
                'title'          => 'Staff Cost Analysis',
                'description'    => 'Review labor costs vs revenue and productivity',
                'priority'       => 'medium',
                'estimated_time' => 25,
                'category'       => 'cost_control',
            ],
            [
                'id'             => 'inventory_valuation',
                'title'          => 'Inventory Valuation',
                'description'    => 'Update inventory values and check for slow-moving items',
                'priority'       => 'medium',
                'estimated_time' => 35,
                'category'       => 'inventory_management',
            ],
        ];

        foreach ($weeklyChecklist as $task) {
            $isCompleted = $this->isTaskCompleted($task['id'], 'weekly', $thisWeek);
            $isOverdue = $this->isTaskOverdue($task['id'], 'weekly', $thisWeek);

            if ($isCompleted) {
                $tasks['completed_tasks'][] = $task;
            } elseif ($isOverdue) {
                $tasks['overdue_tasks'][] = $task;
            } else {
                $tasks['pending_tasks'][] = $task;
            }
        }

        // Weekly alerts
        if ($tasks['metrics']['profit_margin'] < 10) {
            $tasks['alerts'][] = [
                'type'     => 'low_profit_margin',
                'title'    => 'Low Profit Margin Alert',
                'message'  => 'Weekly profit margin is below 10% ('.number_format($tasks['metrics']['profit_margin'], 1).'%)',
                'priority' => 'high',
            ];
        }

        return $tasks;
    }

    private function getMonthlyTasks(): array
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $monthlyRevenue = Sale::whereBetween('created_at', [
            $lastMonth,
            $lastMonth->copy()->endOfMonth(),
        ])->where('status', 'completed')->sum('total_amount');

        $monthlyExpenses = Expense::whereBetween('date', [
            $lastMonth->format('Y-m-d'),
            $lastMonth->copy()->endOfMonth()->format('Y-m-d'),
        ])->sum('amount');

        $tasks = [
            'period'          => 'monthly',
            'month_start'     => $thisMonth->format('Y-m-d'),
            'completed_tasks' => [],
            'pending_tasks'   => [],
            'overdue_tasks'   => [],
            'metrics'         => [
                'last_month_revenue'  => $monthlyRevenue,
                'last_month_expenses' => $monthlyExpenses,
                'monthly_profit'      => $monthlyRevenue - $monthlyExpenses,
                'break_even_days'     => $this->calculateBreakEvenDays($monthlyRevenue, $monthlyExpenses),
            ],
            'reminders' => [],
            'alerts'    => [],
        ];

        $monthlyChecklist = [
            [
                'id'             => 'generate_pnl_statement',
                'title'          => 'Generate P&L Statement',
                'description'    => 'Create comprehensive profit and loss statement',
                'priority'       => 'high',
                'estimated_time' => 60,
                'category'       => 'financial_reporting',
            ],
            [
                'id'             => 'cash_flow_projection',
                'title'          => 'Cash Flow Projection',
                'description'    => 'Project next month\'s cash flow and identify potential issues',
                'priority'       => 'high',
                'estimated_time' => 45,
                'category'       => 'cash_management',
            ],
            [
                'id'             => 'budget_variance_analysis',
                'title'          => 'Budget Variance Analysis',
                'description'    => 'Compare actual vs budgeted performance',
                'priority'       => 'medium',
                'estimated_time' => 40,
                'category'       => 'budgeting',
            ],
            [
                'id'             => 'supplier_performance_review',
                'title'          => 'Supplier Performance Review',
                'description'    => 'Evaluate supplier costs, quality, and payment terms',
                'priority'       => 'medium',
                'estimated_time' => 30,
                'category'       => 'procurement',
            ],
            [
                'id'             => 'tax_preparation',
                'title'          => 'Tax Preparation',
                'description'    => 'Organize documents and calculate tax obligations',
                'priority'       => 'high',
                'estimated_time' => 90,
                'category'       => 'compliance',
            ],
        ];

        foreach ($monthlyChecklist as $task) {
            $isCompleted = $this->isTaskCompleted($task['id'], 'monthly', $thisMonth);
            $isOverdue = $this->isTaskOverdue($task['id'], 'monthly', $thisMonth);

            if ($isCompleted) {
                $tasks['completed_tasks'][] = $task;
            } elseif ($isOverdue) {
                $tasks['overdue_tasks'][] = $task;
            } else {
                $tasks['pending_tasks'][] = $task;
            }
        }

        return $tasks;
    }

    private function getQuarterlyTasks(): array
    {
        $thisQuarter = Carbon::now()->startOfQuarter();
        $lastQuarter = Carbon::now()->subQuarter()->startOfQuarter();

        $quarterlyRevenue = Sale::whereBetween('created_at', [
            $lastQuarter,
            $lastQuarter->copy()->endOfQuarter(),
        ])->where('status', 'completed')->sum('total_amount');

        $quarterlyExpenses = Expense::whereBetween('date', [
            $lastQuarter->format('Y-m-d'),
            $lastQuarter->copy()->endOfQuarter()->format('Y-m-d'),
        ])->sum('amount');

        $tasks = [
            'period'          => 'quarterly',
            'quarter_start'   => $thisQuarter->format('Y-m-d'),
            'completed_tasks' => [],
            'pending_tasks'   => [],
            'overdue_tasks'   => [],
            'metrics'         => [
                'last_quarter_revenue'  => $quarterlyRevenue,
                'last_quarter_expenses' => $quarterlyExpenses,
                'quarterly_profit'      => $quarterlyRevenue - $quarterlyExpenses,
                'growth_rate'           => $this->calculateGrowthRate($quarterlyRevenue),
            ],
            'reminders' => [],
            'alerts'    => [],
        ];

        $quarterlyChecklist = [
            [
                'id'             => 'strategic_financial_review',
                'title'          => 'Strategic Financial Review',
                'description'    => 'Comprehensive review of financial performance and strategy',
                'priority'       => 'high',
                'estimated_time' => 120,
                'category'       => 'strategic_planning',
            ],
            [
                'id'             => 'expansion_readiness_assessment',
                'title'          => 'Expansion Readiness Assessment',
                'description'    => 'Evaluate financial capacity for business expansion',
                'priority'       => 'medium',
                'estimated_time' => 90,
                'category'       => 'growth_planning',
            ],
            [
                'id'             => 'annual_budget_planning',
                'title'          => 'Annual Budget Planning',
                'description'    => 'Plan and set budgets for the upcoming year',
                'priority'       => 'high',
                'estimated_time' => 180,
                'category'       => 'budgeting',
            ],
            [
                'id'             => 'financial_audit_preparation',
                'title'          => 'Financial Audit Preparation',
                'description'    => 'Prepare documents and records for annual audit',
                'priority'       => 'high',
                'estimated_time' => 240,
                'category'       => 'compliance',
            ],
        ];

        foreach ($quarterlyChecklist as $task) {
            $isCompleted = $this->isTaskCompleted($task['id'], 'quarterly', $thisQuarter);
            $isOverdue = $this->isTaskOverdue($task['id'], 'quarterly', $thisQuarter);

            if ($isCompleted) {
                $tasks['completed_tasks'][] = $task;
            } elseif ($isOverdue) {
                $tasks['overdue_tasks'][] = $task;
            } else {
                $tasks['pending_tasks'][] = $task;
            }
        }

        return $tasks;
    }

    private function isTaskCompleted(string $taskId, string $period, Carbon $date): bool
    {
        // This would typically check a task completion tracking table
        // For now, we'll return false to show all tasks as pending
        return false;
    }

    private function isTaskOverdue(string $taskId, string $period, Carbon $date): bool
    {
        $overdueThresholds = [
            'daily'     => 1, // 1 day
            'weekly'    => 3, // 3 days into the week
            'monthly'   => 7, // 1 week into the month
            'quarterly' => 14, // 2 weeks into the quarter
        ];

        $threshold = $overdueThresholds[$period] ?? 1;
        $deadlineDate = $date->copy()->addDays($threshold);

        return Carbon::now()->isAfter($deadlineDate);
    }

    private function calculateBreakEvenDays(float $revenue, float $expenses): int
    {
        if ($revenue <= 0) {
            return 30; // Default to full month if no revenue
        }

        $dailyRevenue = $revenue / 30; // Assuming 30 days in month
        $dailyExpenses = $expenses / 30;

        if ($dailyRevenue <= $dailyExpenses) {
            return 30; // Never breaks even
        }

        return (int) ceil($expenses / $dailyRevenue);
    }

    private function calculateGrowthRate(float $currentRevenue): float
    {
        // This would typically compare with previous quarter
        // For now, return a placeholder calculation
        $previousQuarterRevenue = $currentRevenue * 0.9; // Assume 10% growth

        if ($previousQuarterRevenue <= 0) {
            return 0;
        }

        return (($currentRevenue - $previousQuarterRevenue) / $previousQuarterRevenue) * 100;
    }

    public function getTaskSummary(): array
    {
        $daily = $this->getDailyTasks();
        $weekly = $this->getWeeklyTasks();
        $monthly = $this->getMonthlyTasks();
        $quarterly = $this->getQuarterlyTasks();

        return [
            'total_pending' => count($daily['pending_tasks']) + count($weekly['pending_tasks']) +
                             count($monthly['pending_tasks']) + count($quarterly['pending_tasks']),
            'total_overdue' => count($daily['overdue_tasks']) + count($weekly['overdue_tasks']) +
                              count($monthly['overdue_tasks']) + count($quarterly['overdue_tasks']),
            'total_completed' => count($daily['completed_tasks']) + count($weekly['completed_tasks']) +
                               count($monthly['completed_tasks']) + count($quarterly['completed_tasks']),
            'by_period' => [
                'daily' => [
                    'pending'   => count($daily['pending_tasks']),
                    'overdue'   => count($daily['overdue_tasks']),
                    'completed' => count($daily['completed_tasks']),
                ],
                'weekly' => [
                    'pending'   => count($weekly['pending_tasks']),
                    'overdue'   => count($weekly['overdue_tasks']),
                    'completed' => count($weekly['completed_tasks']),
                ],
                'monthly' => [
                    'pending'   => count($monthly['pending_tasks']),
                    'overdue'   => count($monthly['overdue_tasks']),
                    'completed' => count($monthly['completed_tasks']),
                ],
                'quarterly' => [
                    'pending'   => count($quarterly['pending_tasks']),
                    'overdue'   => count($quarterly['overdue_tasks']),
                    'completed' => count($quarterly['completed_tasks']),
                ],
            ],
        ];
    }
}
