<?php

declare(strict_types=1);

namespace App\Http\Livewire\Purchase;

use App\Models\Purchase;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Show extends Component
{
    public $purchase;

    public $listeners = [
        'showModal',
    ];

    public $showModal = false;

    public function showModal($id)
    {
        $this->purchase = Purchase::findOrFail($id);

        $this->showModal = true;
    }

    public function render()
    {
        // abort_if(Gate::denies('purchase_show'), 403);

        return view('livewire.purchase.show');
    }
}
