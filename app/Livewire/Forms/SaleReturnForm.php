<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SaleReturnForm extends Form
{
    #[Validate('required')]
    public $customer_id;

    #[Validate('required')]
    public $warehouse_id;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('required|numeric')]
    public $paid_amount = 0;

    #[Validate('required|string|max:255')]
    public $payment_method = 'cash';

    public $note;

    #[Validate('required|integer|max:255')]
    public $status;

    #[Validate('required')]
    public $date;

    #[Validate('integer|min:0|max:100')]
    public $tax_percentage = 0;

    #[Validate('integer|min:0|max:100')]
    public $discount_percentage = 0;

    #[Validate('numeric')]
    public $shipping_amount = 0;
}
