<?php

declare(strict_types=1);

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public Supplier $supplier;

    #[On('showModal')]
    public function openModal(int|string $id): void
    {
        abort_if(Gate::denies('supplier_show'), 403);

        $this->supplier = Supplier::query()->findOrFail($id);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.suppliers.show');
    }
}
