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
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class Edit extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $product;

    public $editModal = false;

    public $image;

    public $category_id;

    public $gallery = [];

    public $width = 1000;

    public $height = 1000;

    public $description;

    public $listeners = [
        'editModal',
    ];

    public array $listsForFields = [];

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

    public function mount()
    {
        $this->initListsForFields();
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['brands'] = Brand::pluck('name', 'id')->toArray();
    }

    public function editModal($id)
    {
        abort_if(Gate::denies('product_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = Product::findOrFail($id);

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('product_update'), 403);

        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->product->name).'-'.date('Y-m-d H:i:s').'.'.$this->image->extension();
            $this->image->storeAs('products', $imageName);
            $this->product->image = $imageName;
        }

        $this->product->save();

        $this->emit('refreshIndex');
        
        $this->alert('success', __('Product updated successfully.'));
        
        $this->editModal = false;

    }

    public function getCategoriesProperty()
    {
        return Category::select('name', 'id')->get();
    }

    public function getBrandsProperty()
    {
        return Brand::select('name', 'id')->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::select('name', 'id')->get();
    }

    public function render(): View|Factory
    {
        return view('livewire.products.edit');
    }
}
