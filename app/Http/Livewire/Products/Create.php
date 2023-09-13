<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var array<string> */
    public $listeners = ['createModal'];

    /** @var bool */
    public $createModal = null;

    public $image;

    /** @var mixed */
    public $product;

    public $productWarehouse = [];

    /** @var array */
    protected $rules = [
        'product.name'                => 'required|string|min:3|max:255',
        'product.code'                => 'required|string|max:255',
        'product.barcode_symbology'   => 'required|string|max:255',
        'product.unit'                => 'required|string|max:255',
        'productWarehouse.*.quantity' => 'integer|min:1',
        'productWarehouse.*.price'    => 'numeric',
        'productWarehouse.*.cost'     => 'numeric',
        'product.stock_alert'         => 'required|integer|min:0|max:192',
        'product.order_tax'           => 'nullable|integer|min:0|max:1192',
        'product.tax_type'            => 'nullable|integer|min:0|max:100',
        'product.note'                => 'nullable|string|max:1000',
        'product.category_id'         => 'required|integer|min:0|max:100',
        'product.brand_id'            => 'nullable|integer|min:0|max:100',
        'product.featured'            => 'boolean',

    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function mount(): void
    {
        $this->product = new Product();
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

    public function createModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = new Product();
        $this->product->stock_alert = 10;
        $this->product->order_tax = 0;
        $this->product->unit = 'pcs';
        $this->product->featured = false;
        $this->product->barcode_symbology = 'C128';

        $this->createModal = true;
    }

    public function create(): void
    {
        try {
            $validatedData = $this->validate();

            if ($this->image) {
                $imageName = Str::slug($this->product->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
                $this->image->store('products', $imageName);
                $this->product->image = $imageName;
            }

            $this->product->price = 0;
            $this->product->cost = 0;
            $this->product->quantity = 0;

            $this->product->save($validatedData);

            foreach ($this->productWarehouse as $warehouseId => $warehouse) {
                $quantity = $warehouse['quantity'] ?? 0;
                $price = $warehouse['price'];
                $cost = $warehouse['cost'];

                ProductWarehouse::create([
                    'product_id'   => $this->product->id,
                    'warehouse_id' => $warehouseId,
                    'price'        => $price,
                    'cost'         => $cost,
                    'qty'          => $quantity,
                ]);
            }

            $this->alert('success', __('Product created successfully'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('error', $th->getMessage());
        }
    }

    public function getCategoriesProperty()
    {
        return Category::pluck('name', 'id')->toArray();
    }

    public function getBrandsProperty()
    {
        return Brand::pluck('name', 'id')->toArray();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select(['name', 'id'])->get();
    }
}
