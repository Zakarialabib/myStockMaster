<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Livewire\Utils\WithModels;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    use WithModels;

    public bool $createModal = false;

    public Product $product;

    public $image;

    public ?string $code = null;

    public $gallery;

    #[Validate('required|min:3|max:255')]
    public string $name;

    public string $barcode_symbology = 'C128';

    public ?string $slug = null;

    public string $unit = 'pcs';

    public int $tax_amount = 9;

    public ?string $description = null;

    public ?string $tax_type = null;

    public ?string $usage = null;

    public ?string $embeded_video = null;

    #[Validate('required')]
    public ?int $category_id = null;

    public ?int $brand_id = null;

    public array $options = [];

    #[Validate('nullable|boolean')]
    public ?bool $availability = null;

    #[Validate('nullable|string|max:255')]
    public ?string $seasonality = null;

    #[Validate([
        'productWarehouse.qty' => 'numeric',
        'productWarehouse.price' => 'numeric',
        'productWarehouse.old_price' => 'numeric',
        'productWarehouse.cost' => 'numeric',
        'productWarehouse.stock_alert' => 'numeric',
        'productWarehouse.is_ecommerce' => 'boolean',
    ])]
    public array $productWarehouse = [
        'qty' => 0,
        'price' => 0,
        'cost' => 0,
        'old_price' => 0,
        'stock_alert' => 10,
        'is_ecommerce' => false,
    ];

    public array $selectedAttributes = [];

    #[Computed]
    public function productAttributes()
    {
        return ProductAttribute::all()->mapWithKeys(function ($attr) {
            return [$attr->id => ''];
        })->toArray();
    }

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
            $imageName = Str::slug($this->name) . '-' . $this->image->extension();
            $this->image->storeAs('products', $imageName, 'local_files');
            $this->image = $imageName;
        }

        if ($this->gallery) {
            $gallery = [];

            foreach ($this->gallery as $value) {
                $imageName = Str::slug($this->name) . '-' . Str::random(5) . '.' . $value->extension();
                $value->storeAs('products', $imageName, 'local_files');
                $gallery[] = $imageName;
            }

            $this->gallery = json_encode($gallery);
        }

        $this->description = json_encode($this->description);

        $product = Product::create([
            'name' => $this->name,
            'code' => $this->code,
            'barcode_symbology' => $this->barcode_symbology,
            'slug' => $this->slug,
            'unit' => $this->unit,
            'tax_amount' => $this->tax_amount,
            'description' => $this->description,
            'tax_type' => $this->tax_type,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'availability' => $this->availability,
            'seasonality' => $this->seasonality,
            'image' => $this->image,
            'gallery' => $this->gallery,
        ]);

        ProductWarehouse::create([
            'product_id' => $product->id,
            'warehouse_id' => $this->warehouse?->id,
            'price' => $this->productWarehouse['price'],
            'cost' => $this->productWarehouse['cost'],
            'qty' => $this->productWarehouse['qty'] ?? 0,
            'old_price' => $this->productWarehouse['old_price'],
            'stock_alert' => $this->productWarehouse['stock_alert'] ?? 0,
            'is_ecommerce' => $this->productWarehouse['is_ecommerce'] ?? false,
        ]);

        foreach ($this->selectedAttributes as $id => $value) {
            $product->attributes()->attach($id, ['value' => $value]);
        }

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
