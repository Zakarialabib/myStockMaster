<?php

declare(strict_types=1);

namespace App\Livewire\Analytics;

use App\Actions\Analytics\AnalyzePriceTrendsAction;
use App\Actions\Analytics\GenerateProductAnalyticsAction;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Lazy]
class ProductAnalytics extends Component
{
    use WithPagination;

    #[Validate('required|exists:products,id')]
    public int|string|null $productId = null;

    #[Validate('required|date')]
    public string $dateFrom = '';

    #[Validate('required|date|after_or_equal:dateFrom')]
    public string $dateTo = '';

    public array $analyticsData = [];

    public array $priceTrends = [];

    #[Validate([
        'comparisonProducts' => 'array|max:3',
        'comparisonProducts.*' => 'exists:products,id',
    ])]
    public array $comparisonProducts = [];

    public bool $showComparison = false;

    public function mount($productId = null)
    {
        $this->productId = $productId;
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');

        if ($this->productId) {
            $this->loadProductAnalytics();
        }
    }

    public function updatedProductId()
    {
        $this->validateOnly('productId');
        $this->loadProductAnalytics();
    }

    public function updatedDateFrom()
    {
        $this->validateOnly('dateFrom');

        if ($this->productId) {
            $this->loadProductAnalytics();
        }
    }

    public function updatedDateTo()
    {
        $this->validateOnly('dateTo');

        if ($this->productId) {
            $this->loadProductAnalytics();
        }
    }

    public function loadProductAnalytics()
    {
        if (! $this->productId) {
            return;
        }

        try {
            $product = Product::findOrFail($this->productId);
            $dateFrom = Carbon::parse($this->dateFrom);
            $dateTo = Carbon::parse($this->dateTo);

            // Generate product analytics
            $analyticsAction = new GenerateProductAnalyticsAction;
            $this->analyticsData = $analyticsAction($product, [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]);

            // Get price trends
            $priceTrendsAction = new AnalyzePriceTrendsAction;
            $this->priceTrends = $priceTrendsAction->getPriceHistory($product);

            // Load comparison data if products are selected
            if (! empty($this->comparisonProducts)) {
                $this->loadComparisonData($dateFrom, $dateTo);
            }
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load product analytics: ' . $e->getMessage());
        }
    }

    public function addComparisonProduct($productId)
    {
        if (! in_array($productId, $this->comparisonProducts) && count($this->comparisonProducts) < 3) {
            $this->comparisonProducts[] = $productId;
            $this->showComparison = true;
            $this->loadProductAnalytics();
        }
    }

    public function removeComparisonProduct($productId)
    {
        $this->comparisonProducts = array_filter($this->comparisonProducts, function ($id) use ($productId) {
            return $id != $productId;
        });

        if (empty($this->comparisonProducts)) {
            $this->showComparison = false;
        }

        $this->loadProductAnalytics();
    }

    public function toggleComparison()
    {
        $this->showComparison = ! $this->showComparison;

        if (! $this->showComparison) {
            $this->comparisonProducts = [];
        }
    }

    private function loadComparisonData($dateFrom, $dateTo)
    {
        $analyticsAction = new GenerateProductAnalyticsAction;
        $priceTrendsAction = new AnalyzePriceTrendsAction;

        $this->analyticsData['comparison'] = [];

        foreach ($this->comparisonProducts as $productId) {
            $product = Product::find($productId);

            if ($product) {
                $this->analyticsData['comparison'][$productId] = [
                    'product' => $product,
                    'analytics' => $analyticsAction($product, [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                    ]),
                    'price_trends' => $priceTrendsAction->getPriceHistory($product),
                ];
            }
        }
    }

    public function exportAnalytics()
    {
        try {
            $product = Product::find($this->productId);
            $filename = 'product_analytics_' . $product->code . '_' . now()->format('Y-m-d_H-i-s') . '.json';

            return response()->streamDownload(function () {
                $exportData = [
                    'product_id' => $this->productId,
                    'date_range' => [
                        'from' => $this->dateFrom,
                        'to' => $this->dateTo,
                    ],
                    'analytics' => $this->analyticsData,
                    'price_trends' => $this->priceTrends,
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
            session()->flash('error', 'Failed to export analytics: ' . $e->getMessage());
        }
    }

    #[Computed]
    public function products()
    {
        return Product::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        $selectedProduct = $this->productId ? Product::find($this->productId) : null;

        return view('livewire.analytics.product-analytics', [
            'selectedProduct' => $selectedProduct,
        ]);
    }
}
