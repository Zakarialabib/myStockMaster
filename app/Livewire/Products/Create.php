<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Livewire\Forms\ProductForm;
use App\Livewire\Utils\WithModels;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Isolate]
class Create extends Component
{
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public bool $createModal = false;

    public int $step = 1;

    public ProductForm $form;

    public mixed $gallery = null;

    public array $options = [];

    public ?bool $availability = null;

    public ?string $seasonality = null;

    public ?string $embeded_video = null;

    public ?string $usage = null;

    public array $productWarehouse = [
        'qty' => 0,
        'old_price' => 0,
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
        $this->form->reset();
        $this->step = 1;
        $this->form->unit = 'pcs';
        $this->form->barcode_symbology = 'C128';
        $this->createModal = true;
    }

    public function create(): void
    {
        $this->form->validate();

        $slug = Str::slug($this->form->name);

        $imageName = null;
        if ($this->form->image) {
            $imageName = Str::slug($this->form->name) . '-' . $this->form->image->extension();
            $this->form->image->storeAs('products', $imageName, 'local_files');
        }

        $galleryData = null;
        if ($this->gallery) {
            $gallery = [];

            foreach ($this->gallery as $value) {
                $gName = Str::slug($this->form->name) . '-' . Str::random(5) . '.' . $value->extension();
                $value->storeAs('products', $gName, 'local_files');
                $gallery[] = $gName;
            }

            $galleryData = json_encode($gallery);
        }

        $description = json_encode($this->form->note);

        $product = Product::create([
            'name' => $this->form->name,
            'code' => $this->form->code,
            'barcode_symbology' => $this->form->barcode_symbology,
            'slug' => $slug,
            'unit' => $this->form->unit,
            'tax_amount' => $this->form->order_tax,
            'description' => $description,
            'tax_type' => $this->form->tax_type,
            'category_id' => $this->form->category_id,
            'brand_id' => $this->form->brand_id,
            'availability' => $this->availability,
            'seasonality' => $this->seasonality,
            'image' => $imageName,
            'gallery' => $galleryData,
            'embeded_video' => $this->embeded_video,
            'usage' => $this->usage,
        ]);

        ProductWarehouse::create([
            'product_id' => $product->id,
            'warehouse_id' => $this->warehouse?->id,
            'price' => $this->form->price,
            'cost' => $this->form->cost,
            'qty' => $this->productWarehouse['qty'] ?? 0,
            'old_price' => $this->productWarehouse['old_price'] ?? 0,
            'stock_alert' => $this->form->stock_alert,
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
