<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProduct extends Component
{
    use WithPagination;

    public $product;

    public string $query = '';

    public $category_id;

    public $warehouse_id;

    public $search_results;

    public $showCount = 9;

    public bool $featured = false;

    protected $queryString = [
        'query' => ['except' => ''],
        'category_id' => ['except' => null],
        'warehouse_id' => ['except' => null],
        'showCount' => ['except' => 9],
    ];

    public function mount(): void
    {
        $this->search_results = Collection::empty();
        // $this->warehouse_id = settings()->default_warehouse_id ?? null;
    }

    public function render()
    {
        $products = Product::when($this->category_id, fn ($query, $category_id) => $query->where('category_id', $category_id))
            ->when($this->warehouse_id, fn ($query, $warehouse_id) => $query->where('warehouse_id', $warehouse_id))
            ->when($this->featured, fn ($query, $featured) => $query->where('featured', $featured))
            ->paginate($this->showCount);

        return view('livewire.search-product', [
            'products' => $products
        ]);
    }

    public function resetQuery()
    {
        $this->reset(['query', 'category_id', 'warehouse_id', 'featured']);
    }

    public function updatedQuery()
    {
        $this->search_results = Product::where('name', $this->query)
            ->orWhere('code', $this->query)
            ->take($this->showCount)
            ->get();

        if ($this->search_results->count() > 0) {
            $this->product = $this->search_results->first();
            $this->emit('productSelected', $this->product);
        }
    }

    public function loadMore()
    {
        $this->showCount += 5;
    }

    public function selectProduct($product)
    {
        $this->emit('productSelected', $product);
    }

    public function getCategoriesProperty()
    {
        return  Category::select('name', 'id')->get();
    }
    public function getWarehousesProperty()
    {
        return  Warehouse::select('name', 'id')->get();
    }
}
