<?php

declare(strict_types=1);

namespace App\Livewire\Analytics;

use App\Actions\Analytics\AnalyzePriceTrendsAction;
use App\Actions\Analytics\GenerateProductAnalyticsAction;
use App\Actions\Analytics\GenerateRevenueReportAction;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Exception;

class AnalyticsDashboard extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $selectedProduct = null;
    public $analyticsData = [];
    public $revenueData = [];
    public $priceTrends = [];
    public $loading = false;
    public $search = '';

    protected $rules = [
        'dateFrom'        => 'required|date',
        'dateTo'          => 'required|date|after_or_equal:dateFrom',
        'selectedProduct' => 'nullable|exists:products,id',
    ];

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadAnalytics();
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');
        $this->loadAnalytics();
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');
        $this->loadAnalytics();
    }

    public function updatedSelectedProduct()
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        $this->loading = true;

        try {
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            // Generate revenue report
            $revenueAction = new GenerateRevenueReportAction();
            $this->revenueData = $revenueAction([
                'date_from'          => $dateFrom,
                'date_to'            => $dateTo,
                'include_products'   => true,
                'include_categories' => true,
            ]);

            // If a specific product is selected, get detailed analytics
            if ($this->selectedProduct) {
                $product = Product::find($this->selectedProduct);

                if ($product) {
                    $analyticsAction = new GenerateProductAnalyticsAction();
                    $this->analyticsData = $analyticsAction($product, $dateFrom, $dateTo);

                    $priceTrendsAction = new AnalyzePriceTrendsAction();
                    $this->priceTrends = $priceTrendsAction->getPriceHistory($product);
                }
            }
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load analytics: '.$e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function exportReport($type = 'revenue')
    {
        try {
            $filename = $type.'_report_'.now()->format('Y-m-d_H-i-s').'.json';
            $data = $type === 'revenue' ? $this->revenueData : $this->analyticsData;

            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to export report: '.$e->getMessage());
        }
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.analytics.analytics-dashboard');
    }
}
