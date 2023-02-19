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

    public $listsForFields = [];

    public $categories;

    public $category_id;

    public $warehouse_id;

    public $query;

    public $search_results;

    public $how_many;

    protected $listeners = [
        'selectedCategory' => 'categoryChanged',
        'selectedWarehouse' => 'warehouseChanged',
        'showCount' => 'showCountChanged',
    ];

    public function mount(): void
    {
        $this->query = '';
        $this->how_many = 9;
        $this->search_results = Collection::empty();
        $this->category_id = '';
        $this->warehouse_id = '';
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.search-product', [
            'products' => Product::where('name', 'like', '%'.$this->query.'%')
                ->orWhere('code', 'like', '%'.$this->query.'%')
                ->when($this->category_id, fn ($q) => $q->where('category_id', $this->category_id))
                ->when($this->warehouse_id, fn ($q) => $q->where('warehouse_id', $this->warehouse_id))
                ->paginate($this->how_many),
        ]);
    }

    public function updatedQuery()
    {
        // if the query is like name or code show search results
        $this->search_results = Product::where('name', 'like', '%'.$this->query.'%')
            ->orWhere('code', 'like', '%'.$this->query.'%')
            ->take($this->how_many)
            ->get();
        // issue here
        if ($this->search_results->count() > 0) {
            $this->product = $this->search_results->first();
            $this->emit('productSelected', $this->product);
        }
    }

    public function loadMore()
    {
        $this->how_many += 5;
        $this->updatedQuery();
    }

    public function resetQuery()
    {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function selectProduct($product)
    {
        $this->emit('productSelected', $product);
    }

    public function categoryChanged($category_id)
    {
        $this->category_id = $category_id;
        $this->resetPage();
    }

    public function warehouseChanged($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        $this->resetPage();
    }

    public function showCountChanged($value)
    {
        $this->how_many = $value;
        $this->resetPage();
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
        $this->listsForFields['categories'] = Category::pluck('name', 'id')->toArray();
    }
}
