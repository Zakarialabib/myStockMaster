<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $code = null;

    #[Validate('nullable|string|max:255')]
    public ?string $slug = null;

    #[Validate('required|integer')]
    public ?int $category_id = null;

    #[Validate('nullable|integer')]
    public ?int $brand_id = null;

    #[Validate('required|string|max:255')]
    public string $barcode_symbology = 'C128';

    #[Validate('required|numeric|min:0')]
    public float $cost = 0;

    #[Validate('required|numeric|min:0')]
    public float $price = 0;

    #[Validate('required|string|max:255')]
    public string $unit = 'pcs';

    #[Validate('required|integer|min:0')]
    public int $stock_alert = 10;

    #[Validate('required|integer|min:0')]
    public int $order_tax = 0;

    #[Validate('required|integer|min:0')]
    public int $tax_amount = 0;

    #[Validate('required|integer')]
    public int $tax_type = 0;

    #[Validate('nullable|string')]
    public ?string $note = null;

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('nullable')]
    public $image = null;

    #[Validate('nullable|array')]
    public $gallery = [];

    #[Validate([
        'options.*.type' => '',
        'options.*.value' => '',
    ])]
    public array $options = [];

    #[Validate('nullable|boolean')]
    public ?bool $availability = null;

    #[Validate('nullable|string|max:255')]
    public ?string $seasonality = null;

    #[Validate('nullable|string')]
    public ?string $embeded_video = null;

    #[Validate('nullable|string')]
    public ?string $usage = null;

    #[Validate([
        'productWarehouse.*.qty' => 'numeric',
        'productWarehouse.*.price' => 'numeric',
        'productWarehouse.*.old_price' => 'numeric',
        'productWarehouse.*.cost' => 'numeric',
        'productWarehouse.*.stock_alert' => 'numeric',
        'productWarehouse.*.is_ecommerce' => 'boolean',
    ])]
    public array $productWarehouse = [
        'qty' => 0,
        'old_price' => 0,
        'is_ecommerce' => false,
    ];

    #[Validate('array')]
    public array $selectedAttributes = [];

    #[Validate('boolean')]
    public bool $featured = false;

    #[Validate('boolean')]
    public bool $best = false;

    #[Validate('boolean')]
    public bool $hot = false;

    public function setProduct(Product $product): void
    {
        $this->name = $product->name;
        $this->code = $product->code;
        $this->slug = $product->slug;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->barcode_symbology = $product->barcode_symbology ?? 'C128';
        $this->unit = $product->unit ?? 'pcs';
        $this->tax_amount = $product->tax_amount ?? 0;
        $this->order_tax = $product->tax_amount ?? 0;
        $this->tax_type = $product->tax_type ?? 0;
        $this->description = $product->description;
        $this->note = json_decode($product->description ?? '""', true) ?: $product->description;
        $this->embeded_video = $product->embeded_video;
        $this->usage = $product->usage;
        $this->availability = (bool) $product->availability;
        $this->seasonality = $product->seasonality;
        $this->options = $product->options ?? [['type' => '', 'value' => '']];
        $this->featured = $product->featured;
        $this->best = $product->best;
        $this->hot = $product->hot;

        $this->productWarehouse = $product->warehouses->mapWithKeys(static fn ($warehouse): array => [$warehouse->id => [
            'price' => $warehouse->pivot->price,
            'qty' => $warehouse->pivot->qty,
            'cost' => $warehouse->pivot->cost,
            'old_price' => $warehouse->pivot->old_price,
            'stock_alert' => $warehouse->pivot->stock_alert,
            'is_ecommerce' => $warehouse->pivot->is_ecommerce,
        ]])->toArray();

        // Attributes are not defined on Product model
        // $this->selectedAttributes = $product->attributes->mapWithKeys(function ($attr) {
        //     return [$attr->id => $attr->pivot->value];
        // })->toArray();
    }
}
