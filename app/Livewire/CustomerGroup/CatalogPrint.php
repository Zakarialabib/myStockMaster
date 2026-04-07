<?php

declare(strict_types=1);

namespace App\Livewire\CustomerGroup;

use App\Models\Product;
use App\Traits\WithAlert;
// use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.template')]
class CatalogPrint extends Component
{
    use WithAlert;

    public mixed $data;

    public $entity = 'Catalogue des produits Tahe Cosmetics';

    public function mount(): void
    {
        $this->data = Product::query()->select('id', 'name')->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // abort_if(Gate::denies('Product_show'), 403);

        return view('catalog.template-1');

        // return view('invoice.invoice-5');
    }
}
