<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Livewire\Utils\WithModels;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    use WithModels;

    /** @var bool */
    public $createModal = false;

    public Product $product;

    public $image;

    public $code;

    public $gallery;

    #[Validate('required')]
    #[Validate('min:3')]
    #[Validate('max:255')]
    public $name;

    public $barcode_symbology;

    public $slug;

    public $unit;

    public $tax_amount = 9;

    public $description;

    public $tax_type;

    public $usage;

    public $embeded_video;

    #[Validate('required')]
    public $category_id;

    public $brand_id;

    public $options;

    public $productWarehouse = [
        'qty'          => 0,
        'price'        => 0,
        'cost'         => 0,
        'old_price'    => 0,
        'stock_alert'  => 10,
        'is_ecommerce' => false,
    ];

    /** @var array */
    protected $rules = [
        'productWarehouse.qty'          => 'numeric',
        'productWarehouse.price'        => 'numeric',
        'productWarehouse.old_price'    => 'numeric',
        'productWarehouse.cost'         => 'numeric',
        'productWarehouse.stock_alert'  => 'numeric',
        'productWarehouse.is_ecommerce' => 'boolean',
        'options.*.type'                => '',
        'options.*.value'               => '',
    ];

    public function render()
    {
        abort_if(Gate::denies('product_create'), 403);

        return view('livewire.products.create');
    }

    #[On('createModal')]
    public function openModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();
        $this->unit = 'pcs';
        $this->barcode_symbology = 'C128';
        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->slug = Str::slug($this->name);

        if ($this->image) {
            $imageName = Str::slug($this->name).'-'.$this->image->extension();
            $this->image->storeAs('products', $imageName, 'local_files');
            $this->image = $imageName;
        }

        if ($this->gallery) {
            $gallery = [];

            foreach ($this->gallery as $value) {
                $imageName = Str::slug($this->name).'-'.Str::random(5).'.'.$value->extension();
                $value->storeAs('products', $imageName, 'local_files');
                $gallery[] = $imageName;
            }

            $this->gallery = json_encode($gallery);
        }

        $this->description = json_encode($this->description);

        $product = Product::create(
            $this->all(),
        );

        ProductWarehouse::create([
            'product_id'   => $product->id,
            'warehouse_id' => $this->warehouse?->id,
            'price'        => $this->productWarehouse['price'],
            'cost'         => $this->productWarehouse['cost'],
            'qty'          => $this->productWarehouse['qty'] ?? 0,
            'old_price'    => $this->productWarehouse['old_price'],
            'stock_alert'  => $this->productWarehouse['stock_alert'] ?? 0,
            'is_ecommerce' => $this->productWarehouse['is_ecommerce'] ?? false,
        ]);

        $this->alert('success', __('Product created successfully'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }

    #[Computed]
    public function warehouse()
    {
        return Warehouse::select('name', 'id')->first();
    }

    #[Computed]
    public function categories()
    {
        return Category::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function brands()
    {
        return Brand::pluck('name', 'id')->toArray();
    }
}
