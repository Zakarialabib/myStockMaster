<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Models\Transfer;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public mixed $transfer;

    public bool $showModal = false;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('transfer_show'), 403);

        return view('livewire.transfer.show');
    }

    #[On('showModal')]
    public function showModal(int|string $transfer): void
    {
        $this->transfer = Transfer::with('transferDetails')
            ->where('id', $transfer)->first();

        $this->showModal = true;
    }
}
