<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Models\Adjustment;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Traits\WithAlert;
use Livewire\Attributes\On;

class Show extends Component
{
    use WithAlert;
    public $adjustment;

    public $showModal = false;

    public function render()
    {
        abort_if(Gate::denies('adjustment_show'), 403);

        return view('livewire.adjustment.show');
    }

    #[On('showModal')]
    public function showModal($adjustment): void
    {
        $this->adjustment = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->where('id', $adjustment)->first();

        $this->showModal = true;
    }
}
