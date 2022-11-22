<?php

namespace App\Http\Livewire\Currency;

use App\Models\Currency;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;

    public $listeners = ['createCurrency'];

    public $createCurrency;

    public array $rules = [
        'currency.name' => 'required|string|max:255',
        'currency.code' => 'required|string|max:255',
        'currency.symbol' => 'required|string|max:255',
        'currency.exchange_rate' => 'required|numeric',
    ];

    public function mount(Currency $currency)
    {
        $this->currency = $currency;
    }

    public function render()
    {
        abort_if(Gate::denies('currency_create'), 403);

        return view('livewire.currency.create');
    }

    public function createCurrency()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createCurrency = true;
    }

    public function create()
    {
        $this->validate();

        $this->currency->save();

        $this->alert('success', __('Currency created successfully.'));

        $this->emit('refreshIndex');

        $this->createCurrency = false;
    }
}
