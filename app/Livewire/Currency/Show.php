<?php

declare(strict_types=1);

namespace App\Livewire\Currency;

use App\Models\Currency;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public $showModal = false;

    public $currency;

    #[On('showModal')]
    public function openShowModal($id): void
    {
        abort_if(Gate::denies('currency_show'), 403);

        $this->currency = Currency::find($id);

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.currency.show');
    }
}
