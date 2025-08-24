<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Traits\WithAlert;
// use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.template')]
class Invoice extends Component
{
    use WithAlert;
    public $data;
    public $entity = 'Customer';

    public function mount($id)
    {
        $this->data = Sale::findOrFail($id);
    }

    public function render()
    {
        // abort_if(Gate::denies('sale_show'), 403);

        return view('invoice.'.settings()->invoice_template);

        // return view('invoice.invoice-5');
    }
}
