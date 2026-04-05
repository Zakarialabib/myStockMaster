<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SaleReturnForm extends Form
{
    #[Validate('required')]
    public int|string|null $customer_id = null;

    #[Validate('required')]
    public int|string|null $warehouse_id = null;

    #[Validate('required|numeric')]
    public float|int $total_amount = 0;

    #[Validate('required|numeric')]
    public float|int $paid_amount = 0;

    #[Validate('required|string|max:255')]
    public string $payment_method = 'cash';

    public ?string $note = null;

    #[Validate('required|integer|max:255')]
    public int|string|null $status = null;

    #[Validate('required')]
    public ?string $date = null;

    #[Validate('integer|min:0|max:100')]
    public int|string|null $tax_percentage = 0;

    #[Validate('integer|min:0|max:100')]
    public int|string|null $discount_percentage = 0;

    #[Validate('numeric')]
    public float|int $shipping_amount = 0;
}
