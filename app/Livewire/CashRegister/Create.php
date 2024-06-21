<?php

declare(strict_types=1);

namespace App\Livewire\CashRegister;

use App\Models\CashRegister;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var bool */
    public $createModal = false;

    public CashRegister $cashRegister;

    #[Validate('required', message: 'Please provide a warehouse')]
    public $warehouse_id;

    #[Validate('required', message: 'Please provide a cash in hand')]
    #[Validate('numeric', message: 'Cash in hand must be numeric')]
    public $cash_in_hand;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        CashRegister::create([
            'cash_in_hand' => $this->cash_in_hand,
            'warehouse_id' => $this->warehouse_id,
            'user_id'      => auth()->user()->id,
            'status'       => true,
        ]);

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('CashRegister created successfully.'));

        $this->reset(['cash_in_hand', 'warehouse_id']);

        $this->createModal = false;
    }

    #[Computed]
    public function warehouses()
    {
        return auth()->user()->warehouses;
    }

    public function render()
    {
        // abort_if(Gate::denies('cashRegister_create'), 403);

        return view('livewire.cash-register.create');
    }
}
