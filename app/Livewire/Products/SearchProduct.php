<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProduct extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(as: 'q')]
    public string $querySearch = '';

    public mixed $category_id;

    public mixed $warehouse_id;

    public int $showCount = 9;

    public bool $featured = false;

    public bool $hasMorePages = true;

    public function loadMore(): void
    {
        $this->showCount += 5;
    }

    public function selectProduct(mixed $id): void
    {
        if ($this->warehouse_id !== null) {
            $this->dispatch('productSelected', productId: $id, warehouseId: $this->warehouse_id);
        } else {
            $this->alert('error', __('Please select a warehouse!'));
        }
    }

    #[On('warehouseSelected')]
    public function updatedWarehouseId(int $warehouseId): void
    {
        $this->warehouse_id = $warehouseId;
        $this->resetPage();
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->pluck('name', 'id');
    }

    public function mount(int|string|null $warehouseId = null): void
    {
        if ($warehouseId !== null) {
            $this->warehouse_id = (int) $warehouseId;
        }
    }

    public function resetQuery(): void
    {
        $this->querySearch = '';
    }

    #[On('barcodeScanned')]
    public function handleBarcodeScan(string $barcode): void
    {
        $product = Product::query()->where('code', $barcode)->first();
        if ($product) {
            $this->selectProduct($product->id);
            $this->querySearch = '';
            $this->dispatch('barcode-scanned-success');
        } else {
            $this->querySearch = $barcode;
            $this->dispatch('barcode-scanned-error');
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $query = Product::with(['warehouses' => static function ($query): void {
            $query->withPivot('qty', 'price', 'cost');
        }, 'category'])
            ->when($this->querySearch, function ($query): void {
                $query->where(function (\Illuminate\Contracts\Database\Query\Builder $builder): void {
                    $builder->whereLike('name', '%' . $this->querySearch . '%')
                        ->orWhereLike('code', '%' . $this->querySearch . '%');
                });
            })
            ->when($this->category_id, function ($query): void {
                $query->where('category_id', $this->category_id);
            })
            ->when($this->warehouse_id, function ($query): void {
                $query->whereHas('warehouses', function (\Illuminate\Contracts\Database\Query\Builder $builder): void {
                    $builder->where('warehouse_id', $this->warehouse_id);
                });
            })
            ->when($this->featured, static function ($query): void {
                $query->where('featured', true);
            });

        $lengthAwarePaginator = $query->paginate($this->showCount);
        $this->hasMorePages = $lengthAwarePaginator->hasMorePages();

        return view('livewire.products.search-product', [
            'products' => $lengthAwarePaginator,
        ]);
    }
}
