<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Models\Adjustment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public mixed $adjustment;

    public bool $showModal = false;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('adjustment_show'), 403);

        return view('livewire.adjustment.show');
    }

    #[On('showModal')]
    public function showModal(int|string $adjustment): void
    {
        $this->adjustment = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->where('id', $adjustment)->first();

        $this->showModal = true;
    }
}
