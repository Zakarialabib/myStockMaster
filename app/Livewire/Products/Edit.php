<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $product;

    public $productWarehouses;

    #[Validate('required|string|min:3|max:255')]
    public string $name;

    public string $barcode_symbology;

    public string $code;

    public string $slug;

    public string $unit;

    public $tax_amount;

    public $description;

    public $tax_type;

    public bool $featured = false;

    public $usage;

    public $embeded_video;

    public $category_id;

    public $brand_id;

    #[Validate('array')]
    public array $options = [];

    public $image;

    public $gallery = [];

    public bool $best = false;

    public bool $hot = false;

    public $productWarehouse = [];

    /** @var array */
    protected $rules = [
        'productWarehouse.*.quantity'     => 'numeric',
        'productWarehouse.*.price'        => 'numeric',
        'productWarehouse.*.old_price'    => 'numeric',
        'productWarehouse.*.cost'         => 'numeric',
        'productWarehouse.*.stock_alert'  => 'numeric',
        'productWarehouse.*.is_ecommerce' => 'boolean',
        'options.*.type'                  => '',
        'options.*.value'                 => '',
    ];

    public function addOption(): void
    {
        $this->options[] = [
            'type'  => '',
            'value' => '',
        ];
    }

    public function removeOption($index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function mount($id): void
    {
        $this->product = Product::findOrFail($id);

        $this->code = $this->product->code;
        $this->name = $this->product->name;
        $this->slug = $this->product->slug;
        $this->embeded_video = $this->product->embeded_video;
        $this->category_id = $this->product->category_id;
        $this->brand_id = $this->product->brand_id;
        $this->tax_amount = $this->product->tax_amount;
        $this->tax_type = $this->product->tax_type;
        $this->usage = $this->product->usage;
        $this->unit = $this->product->unit;
        $this->barcode_symbology = $this->product->barcode_symbology;
        $this->description = $this->product->description;

        $this->options = $this->product->options ?? [['type' => '', 'value' => '']];

        $this->productWarehouses = $this->product->warehouses;

        $this->productWarehouse = $this->productWarehouses->mapWithKeys(static fn ($warehouse): array => [$warehouse->id => [
            'price'        => $warehouse->pivot->price,
            'qty'          => $warehouse->pivot->qty,
            'cost'         => $warehouse->pivot->cost,
            'old_price'    => $warehouse->pivot->old_price,
            'stock_alert'  => $warehouse->pivot->stock_alert,
            'is_ecommerce' => $warehouse->pivot->is_ecommerce,
        ]])->toArray();
    }

    public function update(): void
    {
        $this->validate();

        if ($this->slug !== $this->product->slug) {
            $this->slug = Str::slug($this->name);
        }

        if ( ! $this->image) {
            $this->image = null;
        } elseif (is_object($this->image) && method_exists($this->image, 'extension')) {
            $imageName = Str::slug($this->name).'-'.Str::random(5).'.'.$this->image->extension();
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

            $this->gallery = json_encode($gallery, JSON_THROW_ON_ERROR);
        }

        $this->product->update($this->all());

        foreach ($this->productWarehouse as $warehouseId => $warehouse) {
            $this->product->warehouses()->updateExistingPivot($warehouseId, [
                'price'        => $warehouse['price'],
                'qty'          => $warehouse['qty'],
                'cost'         => $warehouse['cost'],
                'old_price'    => $warehouse['old_price'],
                'stock_alert'  => $warehouse['stock_alert'],
                'is_ecommerce' => $warehouse['is_ecommerce'],
            ]);
        }

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Product updated successfully.'));
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

    public function render()
    {
        abort_if(Gate::denies('product update'), 403);

        return view('livewire.products.edit');
    }
}
