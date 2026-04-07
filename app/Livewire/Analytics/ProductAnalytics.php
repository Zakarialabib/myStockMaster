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
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

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

    public function mount(int|string|null $productId = null): void
    {
        $this->productId = $productId;
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');

        if ($this->productId) {
            $this->loadProductAnalytics();
        }
    }

    public function updatedProductId(): void
    {
        $this->validateOnly('productId');
        $this->loadProductAnalytics();
    }

    public function updatedDateFrom(): void
    {
        $this->validateOnly('dateFrom');

        if ($this->productId) {
            $this->loadProductAnalytics();
        }
    }

    public function updatedDateTo(): void
    {
        $this->validateOnly('dateTo');

        if ($this->productId) {
            $this->loadProductAnalytics();
        }
    }

    public function loadProductAnalytics(): void
    {
        if (! $this->productId) {
            return;
        }

        try {
            $product = Product::query()->findOrFail($this->productId);
            $dateFrom = \Illuminate\Support\Facades\Date::parse($this->dateFrom);
            $dateTo = \Illuminate\Support\Facades\Date::parse($this->dateTo);

            // Generate product analytics
            $generateProductAnalyticsAction = new GenerateProductAnalyticsAction;
            $this->analyticsData = $generateProductAnalyticsAction($product, [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]);

            // Get price trends
            $analyzePriceTrendsAction = new AnalyzePriceTrendsAction;
            $this->priceTrends = $analyzePriceTrendsAction->getPriceHistory($product);

            // Load comparison data if products are selected
            if ($this->comparisonProducts !== []) {
                $this->loadComparisonData($dateFrom, $dateTo);
            }
        } catch (Exception $exception) {
            session()->flash('error', 'Failed to load product analytics: ' . $exception->getMessage());
        }
    }

    public function addComparisonProduct(mixed $productId): void
    {
        if (! in_array($productId, $this->comparisonProducts) && count($this->comparisonProducts) < 3) {
            $this->comparisonProducts[] = $productId;
            $this->showComparison = true;
            $this->loadProductAnalytics();
        }
    }

    public function removeComparisonProduct(mixed $productId): void
    {
        $this->comparisonProducts = array_filter($this->comparisonProducts, fn($id) => $id != $productId);

        if ($this->comparisonProducts === []) {
            $this->showComparison = false;
        }

        $this->loadProductAnalytics();
    }

    public function toggleComparison(): void
    {
        $this->showComparison = ! $this->showComparison;

        if (! $this->showComparison) {
            $this->comparisonProducts = [];
        }
    }

    private function loadComparisonData(\Carbon\Carbon $dateFrom, \Carbon\Carbon $dateTo): void
    {
        $generateProductAnalyticsAction = new GenerateProductAnalyticsAction;
        $analyzePriceTrendsAction = new AnalyzePriceTrendsAction;

        $this->analyticsData['comparison'] = [];

        foreach ($this->comparisonProducts as $comparisonProduct) {
            $product = Product::query()->find($comparisonProduct);

            if ($product) {
                $this->analyticsData['comparison'][$comparisonProduct] = [
                    'product' => $product,
                    'analytics' => $generateProductAnalyticsAction($product, [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                    ]),
                    'price_trends' => $analyzePriceTrendsAction->getPriceHistory($product),
                ];
            }
        }
    }

    public function exportAnalytics()
    {
        try {
            $product = Product::query()->find($this->productId);
            $filename = 'product_analytics_' . $product->code . '_' . now()->format('Y-m-d_H-i-s') . '.json';

            return response()->streamDownload(function (): void {
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
        } catch (Exception $exception) {
            session()->flash('error', 'Failed to export analytics: ' . $exception->getMessage());
        }
    }

    #[Computed]
    public function products()
    {
        return Product::query()->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $selectedProduct = $this->productId ? Product::query()->find($this->productId) : null;

        return view('livewire.analytics.product-analytics', [
            'selectedProduct' => $selectedProduct,
        ]);
    }
}
