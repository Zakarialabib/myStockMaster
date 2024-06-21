<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Models\Adjustment;
use Illuminate\Support\Facades\Gate;
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
        abort_if(Gate::denies('adjustment_show'), 403);

        return view('livewire.adjustment.show');
    }

    public function showModal($adjustment): void
    {
        $this->adjustment = Adjustment::with('adjustedProducts', 'adjustedProducts.warehouse', 'adjustedProducts.product')
            ->where('id', $adjustment)->first();

        $this->showModal = true;
    }
}
