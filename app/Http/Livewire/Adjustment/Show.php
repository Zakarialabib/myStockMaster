<?php

namespace App\Http\Livewire\Adjustment;

use App\Models\Adjustment;
use Livewire\Component;

class Show extends Component
{
    public $adjustment;

    public $showModal = false;

    protected $listeners = [
        'showModal',
    ];

    public function render()
    {
        return view('livewire.adjustment.show');
    }

    public function showModal($adjustment): void
    {
        // abort_if(Gate::denies('adjustment_show'), 403);
        $this->adjustment = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
        ->where('id', $adjustment)->first();

        $this->showModal = true;
    }
}
