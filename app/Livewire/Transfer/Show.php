<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Models\Transfer;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $transfer;

    public $showModal = false;

    public function render()
    {
        abort_if(Gate::denies('transfer_show'), 403);

        return view('livewire.transfer.show');
    }

    #[On('showModal')]
    public function showModal($transfer): void
    {
        $this->transfer = Transfer::with('transferDetails')
            ->where('id', $transfer)->first();

        $this->showModal = true;
    }
}
