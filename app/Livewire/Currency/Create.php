<?php

declare(strict_types=1);

namespace App\Livewire\Currency;

use App\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use LivewireAlert;

    public $createModal = false;

    public Currency $currency;

    #[Validate('required', message: 'The name field cannot be empty.')]
    #[Validate('min:3', message: 'The name must be at least 3 characters.')]
    #[Validate('max:255', message: 'The name may not be greater than 255 characters.')]
    public $name;

    #[Validate('required', message: 'The code field cannot be empty.')]
    #[Validate('max:255', message: 'The code may not be greater than 255 characters.')]
    public $code;

    #[Validate('required', message: 'The symbol field cannot be empty.')]
    #[Validate('max:255', message: 'The symbol may not be greater than 255 characters.')]
    public $locale;

    /** @var array */
    public function render()
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

        $this->currency = Currency::create(
            $this->all()
        );

        $this->alert('success', __('Currency created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }
}
