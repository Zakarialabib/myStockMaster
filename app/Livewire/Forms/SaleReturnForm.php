<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SaleReturnForm extends Form
{
    #[Validate('required', message: 'Please provide a customer ID')]
    public ?int $customer_id = null;

    public ?int $warehouse_id = null;

    #[Validate('required', message: 'Please provide a tax percentage')]
    #[Validate('integer', message: 'The tax percentage must be an integer')]
    #[Validate('min:0', message: 'The tax percentage must be at least 0')]
    #[Validate('max:100', message: 'The tax percentage must not exceed 100')]
    public mixed $tax_percentage = 0;

    #[Validate('required', message: 'Please provide a discount percentage')]
    #[Validate('integer', message: 'The discount percentage must be an integer')]
    #[Validate('min:0', message: 'The discount percentage must be at least 0')]
    #[Validate('max:100', message: 'The discount percentage must not exceed 100')]
    public mixed $discount_percentage = 0;

    #[Validate('nullable', message: 'Shipping amount must be a numeric value')]
    public mixed $shipping_amount = 0;

    #[Validate('required', message: 'Please provide a total amount')]
    #[Validate('numeric', message: 'The total amount must be a numeric value')]
    public mixed $total_amount = 0;

    #[Validate('nullable', message: 'Paid amount must be a numeric value')]
    public mixed $paid_amount = 0;

    #[Validate('nullable', message: 'Note must be a string with a maximum length of 1000')]
    #[Validate('string', message: 'Note must be a string')]
    #[Validate('max:1000', message: 'Note must not exceed 1000 characters')]
    public mixed $note;

    #[Validate('required|integer|max:255')]
    public mixed $status;

    #[Validate('required|string|max:255')]
    public mixed $payment_method = 'Cash';

    #[Validate('required')]
    public ?string $date = null;

    public mixed $reference;
}
