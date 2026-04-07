<?php

declare(strict_types=1);

namespace App\Livewire\CashRegister;

use App\Livewire\Forms\CashRegisterForm;
use App\Models\CashRegister;
use App\Services\CashRegisterService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    /** @var bool */
    public bool $createModal = false;

    public CashRegister $cashRegister;

    public CashRegisterForm $form;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(CashRegisterService $cashRegisterService): void
    {
        $this->validate();

        $cashRegisterService->create($this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('CashRegister created successfully.'));

        $this->form->reset();

        $this->createModal = false;
    }

    #[Computed]
    public function warehouses()
    {
        return auth()->user()->warehouses;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // abort_if(Gate::denies('cashRegister_create'), 403);

        return view('livewire.cash-register.create');
    }
}
