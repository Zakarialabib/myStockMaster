<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Models\Quotation;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    /** @var bool */
    public bool $showModal = false;

    public mixed $quotation;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.quotations.show');
    }

    #[On('showModal')]
    public function showModal(int|string $id): void
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $this->quotation = Quotation::query()->findOrFail($id);

        $this->showModal = true;
    }
}
