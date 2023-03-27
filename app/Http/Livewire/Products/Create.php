<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var array<string> */
    public $listeners = ['createProduct'];

    /** @var bool */
    public $createProduct = null;

    public $image;

    /** @var mixed */
    public $product;

    /** @var array */
    protected $rules = [
        'product.name' => 'required|string|min:3|max:255',
        'product.code' => 'required|string|max:255',
        'product.barcode_symbology' => 'required|string|max:255',
        'product.unit' => 'required|string|max:255',
        'product.quantity' => 'required|integer|min:1',
        'product.cost' => 'required|numeric',
        'product.price' => 'required|numeric',
        'product.stock_alert' => 'required|integer|min:0|max:192',
        'product.order_tax' => 'nullable|integer|min:0|max:1192',
        'product.tax_type' => 'nullable|integer|min:0|max:100',
        'product.note' => 'nullable|string|max:1000',
        'product.category_id' => 'required|integer|min:0|max:100',
        'product.brand_id' => 'nullable|integer|min:0|max:100',
        'product.warehouse_id' => 'nullable|integer|min:0|max:100',
        'product.featured' => 'boolean',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->product->stock_alert = 10;
        $this->product->order_tax = 0;
        $this->product->unit = 'pcs';
        $this->product->featured = false;
        $this->product->barcode_symbology = 'C128';
    }

    public function render()
    {
        abort_if(Gate::denies('product_create'), 403);

        return view('livewire.products.create');
    }

    public function createProduct(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createProduct = true;
    }

    public function create(): void
    {
        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->product->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('products', $imageName);
            $this->product->image = $imageName;
        }

        $this->product->save();

        $this->alert('success', __('Product created successfully'));

        $this->emit('refreshIndex');

        $this->createProduct = false;
    }

    public function getCategoriesProperty()
    {
        return Category::select(['name', 'id'])->get();
    }

    public function getBrandsProperty()
    {
        return Brand::select(['name', 'id'])->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select(['name', 'id'])->get();
    }
}
