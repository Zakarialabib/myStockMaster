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

    public bool $showModal = false;

    public mixed $currency;

    #[On('showModal')]
    public function openShowModal(mixed $id): void
    {
        abort_if(Gate::denies('currency_show'), 403);

        $this->currency = Currency::query()->find($id);

        $this->showModal = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.currency.show');
    }
}
