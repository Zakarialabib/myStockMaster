<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SaleForm extends Form
{
    #[Validate('required', message: 'Please provide a customer ID')]
    public $customer_id;

    #[Validate('required', message: 'Please provide a warehouse ID')]
    public $warehouse_id;

    #[Validate('required', message: 'Please provide a tax percentage')]
    #[Validate('integer', message: 'The tax percentage must be an integer')]
    #[Validate('min:0', message: 'The tax percentage must be at least 0')]
    #[Validate('max:100', message: 'The tax percentage must not exceed 100')]
    public $tax_percentage = 0;

    #[Validate('required', message: 'Please provide a discount percentage')]
    #[Validate('integer', message: 'The discount percentage must be an integer')]
    #[Validate('min:0', message: 'The discount percentage must be at least 0')]
    #[Validate('max:100', message: 'The discount percentage must not exceed 100')]
    public $discount_percentage = 0;

    #[Validate('nullable', message: 'Shipping amount must be a numeric value')]
    public $shipping_amount = 0;

    #[Validate('required', message: 'Please provide a total amount')]
    #[Validate('numeric', message: 'The total amount must be a numeric value')]
    public $total_amount = 0;

    #[Validate('nullable', message: 'Paid amount must be a numeric value')]
    public $paid_amount = 0;

    #[Validate('nullable', message: 'Note must be a string with a maximum length of 1000')]
    #[Validate('string', message: 'Note must be a string')]
    #[Validate('max:1000', message: 'Note must not exceed 1000 characters')]
    public $note;

    #[Validate('required|integer|max:255')]
    public $status;

    public string $payment_method = 'cash';

    public $date;

    public $cash_register_id;

    public $user_id;
}
