<?php

declare(strict_types=1);

namespace App\Livewire\CustomerGroup;

use App\Models\Product;
// use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.template')]
class CatalogPrint extends Component
{
    public $data;
    public $entity = 'Catalogue des produits Tahe Cosmetics';

    public function mount()
    {
        $this->data = Product::select('id', 'name')->get();
    }

    public function render()
    {
        // abort_if(Gate::denies('Product_show'), 403);

        return view('catalog.template-1');

        // return view('invoice.invoice-5');
    }
}
