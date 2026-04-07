<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class QuotationForm extends Form
{
    public mixed $reference;

    #[Validate('required')]
    public mixed $customer_id;

    #[Validate('required')]
    public mixed $warehouse_id;

    #[Validate('required|numeric')]
    public mixed $total_amount = 0;

    #[Validate('numeric')]
    public mixed $shipping_amount = 0;

    public mixed $note;

    #[Validate('required|integer|max:255')]
    public mixed $status;

    #[Validate('required')]
    public ?string $date = null;

    #[Validate('integer|min:0|max:100')]
    public mixed $tax_percentage = 0;

    #[Validate('integer|min:0|max:100')]
    public mixed $discount_percentage = 0;
}
