<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CashRegisterForm extends Form
{
    #[Validate('required', message: 'Please provide a warehouse')]
    public $warehouse_id;

    #[Validate('required', message: 'Please provide a cash in hand')]
    #[Validate('numeric', message: 'Cash in hand must be numeric')]
    public $cash_in_hand;
}
