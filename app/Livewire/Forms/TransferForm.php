<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class TransferForm extends Form
{
    #[Validate('required|string|max:255')]
    public mixed $reference;

    #[Validate('required|date')]
    public ?string $date = null;

    #[Validate('nullable|string')]
    public mixed $user_id;

    #[Validate('required|integer')]
    public mixed $from_warehouse_id;

    #[Validate('required|integer')]
    public mixed $to_warehouse_id;

    #[Validate('required|numeric')]
    public mixed $total_qty = 0;

    #[Validate('required|numeric')]
    public mixed $total_cost = 0;

    #[Validate('required|numeric')]
    public mixed $total_amount = 0;

    #[Validate('nullable|numeric')]
    public mixed $shipping_amount = 0;

    #[Validate('nullable|string|max:255')]
    public mixed $document;

    #[Validate('required|integer')]
    public mixed $status = 1;

    #[Validate('nullable|string|max:1000')]
    public mixed $note;
}
