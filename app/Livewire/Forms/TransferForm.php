<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class TransferForm extends Form
{
    #[Validate('required|string|max:255')]
    public $reference;

    #[Validate('required|date')]
    public $date;

    #[Validate('nullable|string')]
    public $user_id;

    #[Validate('required|integer')]
    public $from_warehouse_id;

    #[Validate('required|integer')]
    public $to_warehouse_id;

    #[Validate('required|numeric')]
    public $total_qty = 0;

    #[Validate('required|numeric')]
    public $total_cost = 0;

    #[Validate('required|numeric')]
    public $total_amount = 0;

    #[Validate('nullable|numeric')]
    public $shipping_amount = 0;

    #[Validate('nullable|string|max:255')]
    public $document;

    #[Validate('required|integer')]
    public $status = 1;

    #[Validate('nullable|string|max:1000')]
    public $note;
}
