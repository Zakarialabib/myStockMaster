<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Traits\WithAlert;
// use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.template')]
class Invoice extends Component
{
    use WithAlert;

    public mixed $data;

    public $entity = 'Customer';

    public function mount(int|string $id): void
    {
        $this->data = Sale::query()->findOrFail($id);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // abort_if(Gate::denies('sale_show'), 403);

        return view('invoice.' . settings()->invoice_template);

        // return view('invoice.invoice-5');
    }
}
