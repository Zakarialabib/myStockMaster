<?php

declare(strict_types=1);

namespace App\Livewire\Currency;

use App\Models\Currency;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public bool $createModal = false;

    public Currency $currency;

    #[Validate('required', message: 'The name field cannot be empty.')]
    #[Validate('min:3', message: 'The name must be at least 3 characters.')]
    #[Validate('max:255', message: 'The name may not be greater than 255 characters.')]
    public mixed $name;

    #[Validate('required', message: 'The code field cannot be empty.')]
    #[Validate('max:255', message: 'The code may not be greater than 255 characters.')]
    public mixed $code;

    #[Validate('required', message: 'The symbol field cannot be empty.')]
    #[Validate('max:255', message: 'The symbol may not be greater than 255 characters.')]
    public mixed $locale;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('currency_create'), 403);

        return view('livewire.currency.create');
    }

    #[On('createModal')]
    public function openCreateModal(): void
    {
        abort_if(Gate::denies('currency_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->currency = Currency::query()->create($this->all());

        $this->alert('success', __('Currency created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }
}
