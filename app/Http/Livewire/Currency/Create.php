<?php

declare(strict_types=1);

namespace App\Http\Livewire\Currency;

use App\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createModal'];

    public $createModal = false;

    /** @var mixed */
    public $currency;

    /** @var array */
    protected $rules = [
        'currency.name'          => 'required|string|min:3|max:255',
        'currency.code'          => 'required|string|max:255',
        'currency.symbol'        => 'required|string|max:255',
        'currency.exchange_rate' => 'required|numeric',
    ];

    protected $messages = [
        'currency.name.required'          => 'The name field cannot be empty.',
        'currency.code.required'          => 'The code field cannot be empty.',
        'currency.symbol.required'        => 'The symbol field cannot be empty.',
        'currency.exchange_rate.required' => 'The exchange rate field cannot be empty.',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        abort_if(Gate::denies('currency_create'), 403);

        return view('livewire.currency.create');
    }

    public function createModal(): void
    {
        abort_if(Gate::denies('currency_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->currency = new Currency();

        $this->createModal = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        try {
            $this->currency->save($validatedData);

            $this->alert('success', __('Currency created successfully.'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Error.').$th->getMessage());
        }
    }
}
