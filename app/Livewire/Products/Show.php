<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\Product;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public ?Product $product = null;

    public bool $showModal = false;

    #[On('showModal')]
    public function openModal(int|string $id): void
    {
        $this->product = Product::with(['category', 'warehouses', 'movements'])->findOrFail($id);

        $this->showModal = true;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('product_show'), 403);

        return view('livewire.products.show');
    }
}
