<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Models\Purchase;
// use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.template')]
class Invoice extends Component
{
    public $data;
    public $entity = 'Supplier';

    public function mount($id)
    {
        $this->data = Purchase::findOrFail($id);
    }

    public function render()
    {
        // abort_if(Gate::denies('purchase_show'), 403);

        return view('invoice.'.settings()->invoice_template);
    }
}
