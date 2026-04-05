<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PosCheckoutForm extends Form
{
    #[Validate('required', message: 'Please provide a customer ID')]
    public int|string|null $customer_id = null;

    #[Validate('required', message: 'Please provide a warehouse ID')]
    public int|string|null $warehouse_id = null;

    #[Validate('required|integer|min:0|max:100', message: ['required' => 'Please provide a tax percentage'])]
    public int $tax_percentage = 0;

    #[Validate('required|integer|min:0|max:100', message: ['required' => 'Please provide a discount percentage'])]
    public int $discount_percentage = 0;

    #[Validate('nullable|numeric', message: ['numeric' => 'Shipping amount must be a numeric value'])]
    public float|int $shipping_amount = 0;

    #[Validate('required|numeric', message: ['required' => 'Please provide a total amount'])]
    public float|int $total_amount = 0;

    #[Validate('nullable|numeric', message: ['numeric' => 'Paid amount must be a numeric value'])]
    public float|int $paid_amount = 0;

    public string $payment_method = 'cash';

    #[Validate('nullable|string|max:1000', message: ['max' => 'Note must not exceed 1000 characters'])]
    public ?string $note = null;
}
