<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $code = '';

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

    #[Validate('required|integer')]
    public int $tax_type = 0;

    #[Validate('nullable|string')]
    public ?string $note = null;

    #[Validate('nullable|image|max:2048')]
    public $image = null;
}
