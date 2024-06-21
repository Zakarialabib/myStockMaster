<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Models\Quotation;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    /** @var bool */
    public $showModal = false;

    public $quotation;

    public function render()
    {
        return view('livewire.quotations.show');
    }

    #[On('showModal')]
    public function showModal($id): void
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $this->quotation = Quotation::findOrFail($id);

        $this->showModal = true;
    }
}
