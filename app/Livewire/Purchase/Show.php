<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public Purchase $purchase;

    public bool $showModal = false;

    #[On('showModal')]
    public function showModal($id): void
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
