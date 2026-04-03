<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PurchaseForm extends Form
{
    #[Validate('required')]
    public $warehouse_id;

    #[Validate('required')]
    public $supplier_id;

    #[Validate('required|integer|min:0|max:100')]
    public $tax_percentage = 0;

    #[Validate('required|integer|min:0|max:100')]
    public $discount_percentage = 0;

    #[Validate('required|numeric')]
    public $shipping_amount = 0;

    #[Validate('required|numeric')]
    public $total_amount = 0;

    #[Validate('required|numeric')]
    public $paid_amount = 0;

    #[Validate('required')]
    public $status;

    #[Validate('required|string|max:50')]
    public $payment_method = 'cash';

    #[Validate('nullable|string|max:1000')]
    public $note;

    public $date;
}
