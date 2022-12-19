<?php

declare(strict_types=1);

namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var string[] */
    public $listeners = ['createProduct'];

    /** @var bool */
    public $createProduct = null;

    public $image;

    public array $listsForFields = [];

    /** @var mixed */
    public $product;

    public array $rules = [
        'product.name'              => ['required', 'string', 'max:255'],
        'product.code'              => ['required', 'string', 'max:255'],
        'product.barcode_symbology' => ['required', 'string', 'max:255'],
        'product.unit'              => ['required', 'string', 'max:255'],
        'product.quantity'          => ['required', 'integer', 'min:1'],
        'product.cost'              => ['required', 'numeric', 'max:2147483647'],
        'product.price'             => ['required', 'numeric', 'max:2147483647'],
        'product.stock_alert'       => ['required', 'integer', 'min:0'],
        'product.order_tax'         => ['nullable', 'integer', 'min:0', 'max:100'],
        'product.tax_type'          => ['nullable', 'integer'],
        'product.note'              => ['nullable', 'string', 'max:1000'],
        'product.category_id'       => ['required', 'integer'],
        'product.brand_id'          => ['nullable', 'integer'],
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
        $this->product->barcode_symbology = 'C128';
        $this->initListsForFields();
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('create_products'), 403);

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

    protected function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::pluck('name', 'id')->toArray();
        $this->listsForFields['brands'] = Brand::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }
}
