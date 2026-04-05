<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class QuotationForm extends Form
{
    #[Validate('required|integer')]
    public ?int $customer_id = null;

    #[Validate('required|integer')]
    public ?int $warehouse_id = null;

    #[Validate('required|numeric')]
    public float|int $total_amount = 0;

    #[Validate('numeric')]
    public float|int $shipping_amount = 0;

    #[Validate('nullable|string')]
    public ?string $note = null;

    #[Validate('required|string|max:255')]
    public ?string $status = null;

    #[Validate('required|date')]
    public ?string $date = null;

    #[Validate('integer|min:0|max:100')]
    public int $tax_percentage = 0;

    #[Validate('integer|min:0|max:100')]
    public int $discount_percentage = 0;
}
