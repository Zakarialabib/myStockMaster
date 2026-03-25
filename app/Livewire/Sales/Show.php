<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public $sale;

    public $showModal = false;

    #[On('showModal')]
    public function showModal($id): void
    {
        $this->sale = Sale::findOrFail($id);

        $this->showModal = true;
    }

    public function render()
    {
        abort_if(Gate::denies('sale_show'), 403);

        return view('livewire.sales.show');
    }
}
