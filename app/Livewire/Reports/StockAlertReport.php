<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class StockAlertReport extends Component
{
    use WithAlert;
    use WithPagination;

    public array $thresholds = [];

    public string $filterName = '';

    public string $filterCode = '';

    public ?int $filterQuantityMin = null;

    public ?int $filterQuantityMax = null;

    #[Computed]
    public function stockAlert()
    {
        $query = Product::belowStockAlert();

        if ($this->filterName) {
            $query->where('name', 'like', '%' . $this->filterName . '%');
        }

        if ($this->filterCode) {
            $query->where('code', 'like', '%' . $this->filterCode . '%');
        }

        if ($this->filterQuantityMin !== null) {
            $query->where('quantity', '>=', $this->filterQuantityMin);
        }

        if ($this->filterQuantityMax !== null) {
            $query->where('quantity', '<=', $this->filterQuantityMax);
        }

        return $query->paginate();
    }

    public function setThreshold($productId, $threshold)
    {
        $product = Product::find($productId);

        if ($product) {
            $product->stock_alert = $threshold;
            $product->save();
        }
    }

    public function render()
    {
        return view('livewire.reports.stock-alert-report', [
            'products' => $this->stockAlert(),
        ]);
    }
}
