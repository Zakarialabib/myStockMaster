<?php

declare(strict_types=1);

namespace App\Livewire\Currency;

use App\Models\Currency;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

    public bool $editModal = false;

    /** @var mixed */
    public mixed $currency;

    #[Validate('required', message: 'The name field cannot be empty.')]
    #[Validate('min:3', message: 'The name must be at least 3 characters.')]
    #[Validate('max:255', message: 'The name may not be greater than 255 characters.')]
    public mixed $name;

    #[Validate('required', message: 'The code field cannot be empty.')]
    #[Validate('max:255', message: 'The code may not be greater than 255 characters.')]
    public mixed $code;

    #[Validate('required', message: 'The locale field cannot be empty.')]
    #[Validate('max:255', message: 'The locale may not be greater than 255 characters.')]
    public mixed $locale;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('currency_update'), 403);

        return view('livewire.currency.edit');
    }

    #[On('editModal')]
    public function openEditModal(mixed $id): void
    {
        abort_if(Gate::denies('currency_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->currency = Currency::query()->where('id', $id)->firstOrFail();

        $this->name = $this->currency->name;

        $this->code = $this->currency->code;

        $this->locale = $this->currency->locale;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->currency->update(
            $this->all(),
        );

        $this->alert('success', __('Currency updated successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
