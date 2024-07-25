<?php

declare(strict_types=1);

namespace App\Livewire\Currency;

use App\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    public $editModal = false;

    /** @var mixed */
    public $currency;

    #[Validate('required', message: 'The name field cannot be empty.')]
    #[Validate('min:3', message: 'The name must be at least 3 characters.')]
    #[Validate('max:255', message: 'The name may not be greater than 255 characters.')]
    public $name;

    #[Validate('required', message: 'The code field cannot be empty.')]
    #[Validate('max:255', message: 'The code may not be greater than 255 characters.')]
    public $code;

    #[Validate('required', message: 'The locale field cannot be empty.')]
    #[Validate('max:255', message: 'The locale may not be greater than 255 characters.')]
    public $locale;

    public function render()
    {
        abort_if(Gate::denies('currency_update'), 403);

        return view('livewire.currency.edit');
    }

    #[On('editModal')]
    public function openEditModal($id): void
    {
        abort_if(Gate::denies('currency_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->currency = Currency::where('id', $id)->firstOrFail();

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
