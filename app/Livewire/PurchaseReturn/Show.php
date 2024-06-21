<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $purchasereturn;

    public $showModal = false;

    #[On('showModal')]
    public function showModal($id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = PurchaseReturn::whereId($id)->firstOrFail();

        $this->showModal = true;
    }

    public function render()
    {
        abort_if(Gate::denies('purchase_access'), 403);

        return view('livewire.purchase-return.show');
    }
}
