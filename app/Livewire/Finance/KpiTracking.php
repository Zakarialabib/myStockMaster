<?php

declare(strict_types=1);

namespace App\Livewire\Finance;

use Carbon\Carbon;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('KPI Tracking')]

#[Layout('layouts.app')]
class KpiTracking extends Component
{
    use WithPagination;

    #[Validate('required|date')]
    public string $dateFrom;

    #[Validate('required|date|after_or_equal:dateFrom')]
    public string $dateTo;

    #[Validate('required|in:revenue,profitability,efficiency,growth')]
    public string $kpiType = 'revenue';

    #[Validate('required|in:previous,year_ago,custom')]
    public string $comparisonPeriod = 'previous';

    public array $kpiData = [];

    public array $comparisonData = [];

    public bool $loading = false;

    public bool $autoRefresh = false;

    public int $refreshInterval = 60; // seconds

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadKpiData();
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->loadKpiData();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->loadKpiData();
    }

    public function updatedKpiType()
    {
        $this->validateOnly('kpiType');
        $this->loadKpiData();
    }

    public function updatedComparisonPeriod()
    {
        $this->validateOnly('comparisonPeriod');
        $this->loadKpiData();
    }

    public function loadKpiData()
    {
        $this->loading = true;

        try {
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            switch ($this->kpiType) {
                case 'revenue':
                    $this->kpiData = app(\App\Services\AnalyticsService::class)->getRevenueKpis($dateFrom, $dateTo);

                    break;
                case 'profitability':
                    $this->kpiData = app(\App\Services\AnalyticsService::class)->getProfitabilityKpis($dateFrom, $dateTo);

                    break;
                case 'efficiency':
                    $this->kpiData = app(\App\Services\AnalyticsService::class)->getEfficiencyKpis($dateFrom, $dateTo);

                    break;
                case 'growth':
                    $this->kpiData = app(\App\Services\AnalyticsService::class)->getGrowthKpis($dateFrom, $dateTo);

                    break;
            }

            // Load comparison data
            $this->loadComparisonData($dateFrom, $dateTo);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load KPI data: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    private function loadComparisonData($dateFrom, $dateTo)
    {
        $periodDays = $dateFrom->diffInDays($dateTo);

        switch ($this->comparisonPeriod) {
            case 'previous':
                $comparisonDateFrom = $dateFrom->copy()->subDays($periodDays + 1);
                $comparisonDateTo = $dateFrom->copy()->subDay();

                break;
            case 'year_ago':
                $comparisonDateFrom = $dateFrom->copy()->subYear();
                $comparisonDateTo = $dateTo->copy()->subYear();

                break;
            default:
                return;
        }

        $cacheKey = 'kpi_compare_' . $this->kpiType . '_' . $this->comparisonPeriod . '_' . $dateFrom->format('Ymd') . '_' . $dateTo->format('Ymd');

        $this->comparisonData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($comparisonDateFrom, $comparisonDateTo) {
            switch ($this->kpiType) {
                case 'revenue':
                    return app(\App\Services\AnalyticsService::class)->getRevenueKpis($comparisonDateFrom, $comparisonDateTo);
                case 'profitability':
                    return app(\App\Services\AnalyticsService::class)->getProfitabilityKpis($comparisonDateFrom, $comparisonDateTo);
                case 'efficiency':
                    return app(\App\Services\AnalyticsService::class)->getEfficiencyKpis($comparisonDateFrom, $comparisonDateTo);
                case 'growth':
                    return app(\App\Services\AnalyticsService::class)->getGrowthKpis($comparisonDateFrom, $comparisonDateTo);
                default:
                    return [];
            }
        });
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = ! $this->autoRefresh;

        if ($this->autoRefresh) {
            $this->dispatch('start-auto-refresh', interval: $this->refreshInterval * 1000);
        } else {
            $this->dispatch('stop-auto-refresh');
        }
    }

    public function refreshKpis()
    {
        $this->loadKpiData();
        $this->dispatch('kpi-data-refreshed');
    }

    public function exportKpiData()
    {
        try {
            $filename = 'kpi_' . $this->kpiType . '_' . now()->format('Y-m-d_H-i-s') . '.json';

            return response()->streamDownload(function () {
                $exportData = [
                    'kpi_type' => $this->kpiType,
                    'date_range' => [
                        'from' => $this->dateFrom,
                        'to' => $this->dateTo,
                    ],
                    'comparison_period' => $this->comparisonPeriod,
                    'current_data' => $this->kpiData,
                    'comparison_data' => $this->comparisonData,
                    'generated_at' => now()->toISOString(),
                ];

                $generator = function () use ($exportData) {
                    yield '{';
                    $first = true;
                    foreach ($exportData as $key => $value) {
                        if (! $first) {
                            yield ',';
                        }
                        yield '"' . $key . '":' . json_encode($value);
                        $first = false;
                    }
                    yield '}';
                };

                foreach ($generator() as $chunk) {
                    echo $chunk;
                }
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to export KPI data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.finance.kpi-tracking');
    }
}
