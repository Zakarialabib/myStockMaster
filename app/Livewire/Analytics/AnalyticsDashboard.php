<?php

declare(strict_types=1);

namespace App\Livewire\Analytics;

use App\Actions\Analytics\AnalyzePriceTrendsAction;
use App\Actions\Analytics\GenerateProductAnalyticsAction;
use App\Actions\Analytics\GenerateRevenueReportAction;
use App\Models\Product;
use App\Traits\WithAlert;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class AnalyticsDashboard extends Component
{
    use WithAlert;
    use WithPagination;

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    #[Validate('required|date')]
    public string $dateFrom = '';

    #[Validate('required|date|after_or_equal:dateFrom')]
    public string $dateTo = '';

    public ?int $selectedProduct = null;

    public array $analyticsData = [];

    public array $revenueData = [];

    public array $priceTrends = [];

    public string $search = '';

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadAnalytics();
    }

    public function updatedDateFrom(): void
    {
        $this->validateOnly('dateFrom');
        $this->loadAnalytics();
    }

    public function updatedDateTo(): void
    {
        $this->validateOnly('dateTo');
        $this->loadAnalytics();
    }

    public function updatedSelectedProduct(): void
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics(): void
    {

        try {
            $dateFrom = \Illuminate\Support\Facades\Date::parse($this->dateFrom);
            $dateTo = \Illuminate\Support\Facades\Date::parse($this->dateTo);

            $this->revenueData = resolve(GenerateRevenueReportAction::class)([
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'include_products' => true,
                'include_categories' => true,
            ]);

            if ($this->selectedProduct) {
                $product = Product::query()->find($this->selectedProduct);

                if ($product) {
                    $this->analyticsData = resolve(GenerateProductAnalyticsAction::class)($product, $dateFrom, $dateTo);

                    $this->priceTrends = resolve(AnalyzePriceTrendsAction::class)->getPriceHistory($product);
                }
            }
        } catch (Throwable $throwable) {
            $this->alert('error', __('Failed to load analytics.') . ' ' . $throwable->getMessage());
        }
    }

    public function exportReport(string $type = 'revenue')
    {
        try {
            $filename = $type . '_report_' . now()->format('Y-m-d_H-i-s') . '.json';
            $data = $type === 'revenue' ? $this->revenueData : $this->analyticsData;

            return response()->streamDownload(function () use ($data): void {
                $generator = function () use ($data) {
                    yield '{';
                    $first = true;
                    foreach ($data as $key => $value) {
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
        } catch (Throwable $throwable) {
            $this->alert('error', __('Failed to export report.') . ' ' . $throwable->getMessage());
        }
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, function ($query): void {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.analytics.analytics-dashboard');
    }
}
