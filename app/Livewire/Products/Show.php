<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $product;

    public $showModal = false;

    #[On('showModal')]
    public function openModal($id): void
    {
        $this->product = Product::where('id', $id)->first();

        $this->showModal = true;
    }

    public function render()
    {
        abort_if(Gate::denies('product_show'), 403);

        return view('livewire.products.show');
    }
}
