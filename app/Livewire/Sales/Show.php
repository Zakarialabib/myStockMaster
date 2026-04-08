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

    public Sale $sale;

    public bool $showModal = false;

    #[On('showModal')]
    public function showModal(int|string $id): void
    {
        $this->sale = Sale::query()->findOrFail($id);

        $this->showModal = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('sale_show'), 403);

        return view('livewire.sales.show');
    }
}
