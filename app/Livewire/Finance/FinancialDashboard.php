<?php

declare(strict_types=1);

namespace App\Livewire\Finance;

use App\Actions\Finance\CalculateBreakEvenAction;
use App\Actions\Finance\CalculateCashFlowAction;
use App\Actions\Finance\CalculateGrossMarginAction;
use App\Actions\Finance\CalculateNetMarginAction;
use App\Actions\Finance\GenerateFinancialKpiReportAction;
use App\Actions\Finance\GenerateFinancialReportsAction;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Exception;

class FinancialDashboard extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $dateRange = 'month';
    public $startDate;
    public $endDate;
    public $kpiData = [];
    public $profitLossData = [];
    public $cashFlowData = [];
    public $breakEvenData = [];
    public $grossMarginData = [];
    public $loading = false;
    public $search = '';
    public $refreshInterval = 300; // 5 minutes

    protected $rules = [
        'dateFrom'  => 'required|date',
        'dateTo'    => 'required|date|after_or_equal:dateFrom',
        'startDate' => 'nullable|date',
        'endDate'   => 'nullable|date|after_or_equal:startDate',
    ];

    public function mount()
    {
        $this->setDateRange();
        $this->loadFinancialData();
    }

    public function updatedDateRange()
    {
        $this->setDateRange();
        $this->loadFinancialData();
    }

    public function updatedStartDate()
    {
        if ($this->dateRange === 'custom') {
            $this->validateOnly('startDate');
            $this->dateFrom = $this->startDate;
            $this->loadFinancialData();
        }
    }

    public function updatedEndDate()
    {
        if ($this->dateRange === 'custom') {
            $this->validateOnly('endDate');
            $this->dateTo = $this->endDate;
            $this->loadFinancialData();
        }
    }

    private function setDateRange()
    {
        switch ($this->dateRange) {
            case 'today':
                $this->dateFrom = now()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');

                break;
            case 'week':
                $this->dateFrom = now()->startOfWeek()->format('Y-m-d');
                $this->dateTo = now()->endOfWeek()->format('Y-m-d');

                break;
            case 'month':
                $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'quarter':
                $this->dateFrom = now()->startOfQuarter()->format('Y-m-d');
                $this->dateTo = now()->endOfQuarter()->format('Y-m-d');

                break;
            case 'year':
                $this->dateFrom = now()->startOfYear()->format('Y-m-d');
                $this->dateTo = now()->endOfYear()->format('Y-m-d');

                break;
            case 'custom':
                if ( ! $this->startDate) {
                    $this->startDate = now()->subDays(30)->format('Y-m-d');
                }

                if ( ! $this->endDate) {
                    $this->endDate = now()->format('Y-m-d');
                }
                $this->dateFrom = $this->startDate;
                $this->dateTo = $this->endDate;

                break;
            default:
                $this->dateFrom = now()->subDays(30)->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
        }
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->loadFinancialData();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->loadFinancialData();
    }

    public function loadFinancialData()
    {
        $this->loading = true;

        try {
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            // Load KPI data using action
            $kpiAction = new GenerateFinancialKpiReportAction(
                new CalculateGrossMarginAction(),
                new CalculateNetMarginAction(),
                new CalculateCashFlowAction(),
                new CalculateBreakEvenAction()
            );
            $this->kpiData = $kpiAction($dateFrom, $dateTo);

            // Generate profit & loss report
            $profitLossAction = new GenerateFinancialReportsAction();
            $this->profitLossData = $profitLossAction([
                'report_type' => 'profit_loss',
                'start_date'  => $dateFrom,
                'end_date'    => $dateTo,
                'period'      => 'daily',
            ]);

            // Generate cash flow report
            $cashFlowAction = new CalculateCashFlowAction();
            $this->cashFlowData = $cashFlowAction($dateFrom, $dateTo);

            // Calculate break-even analysis
            $breakEvenAction = new CalculateBreakEvenAction();
            $this->breakEvenData = $breakEvenAction([
                'rent'                   => 0,
                'salaries'               => 0,
                'insurance'              => 0,
                'utilities_fixed'        => 0,
                'equipment_lease'        => 0,
                'software_subscriptions' => 0,
                'marketing_fixed'        => 0,
                'other_fixed'            => 0,
            ], $dateTo);

            // Calculate gross margin
            $grossMarginAction = new CalculateGrossMarginAction();
            $this->grossMarginData = $grossMarginAction($dateFrom, $dateTo);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load financial data: '.$e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    #[Computed]
    public function filteredProducts()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);
    }

    #[Computed]
    public function totalRevenue()
    {
        return $this->kpiData['total_revenue'] ?? 0;
    }

    #[Computed]
    public function totalExpenses()
    {
        return $this->kpiData['total_expenses'] ?? 0;
    }

    #[Computed]
    public function netProfit()
    {
        return $this->totalRevenue - $this->totalExpenses;
    }

    public function refreshData()
    {
        $this->loadFinancialData();
        $this->dispatch('financial-data-refreshed');
    }

    public function exportFinancialReport($type = 'complete')
    {
        try {
            $filename = 'financial_report_'.$type.'_'.now()->format('Y-m-d_H-i-s').'.json';

            $exportData = [
                'report_type' => $type,
                'date_range'  => [
                    'from' => $this->dateFrom,
                    'to'   => $this->dateTo,
                ],
                'kpi_data'     => $this->kpiData,
                'generated_at' => now()->toISOString(),
            ];

            switch ($type) {
                case 'kpi':
                    $exportData['data'] = $this->kpiData;

                    break;
                case 'break_even':
                    $exportData['data'] = $this->breakEvenData;

                    break;
                case 'gross_margin':
                    $exportData['data'] = $this->grossMarginData;

                    break;
                case 'complete':
                default:
                    $exportData['profit_loss_data'] = $this->profitLossData;
                    $exportData['cash_flow_data'] = $this->cashFlowData;
                    $exportData['break_even_data'] = $this->breakEvenData;
                    $exportData['gross_margin_data'] = $this->grossMarginData;

                    break;
            }

            return response()->streamDownload(function () use ($exportData) {
                echo json_encode($exportData, JSON_PRETTY_PRINT);
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to export financial report: '.$e->getMessage());
        }
    }

    #[Computed]
    public function chartData()
    {
        if ( ! isset($this->profitLossData['monthly_breakdown'])) {
            return [];
        }

        $labels = [];
        $revenues = [];
        $expenses = [];
        $profits = [];

        foreach ($this->profitLossData['monthly_breakdown'] as $month => $data) {
            $labels[] = $month;
            $revenues[] = $data['revenue'] ?? 0;
            $expenses[] = $data['expenses'] ?? 0;
            $profits[] = ($data['revenue'] ?? 0) - ($data['expenses'] ?? 0);
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Revenue',
                    'data'            => $revenues,
                    'borderColor'     => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
                [
                    'label'           => 'Expenses',
                    'data'            => $expenses,
                    'borderColor'     => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
                [
                    'label'           => 'Net Profit',
                    'data'            => $profits,
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.finance.financial-dashboard');
    }
}
