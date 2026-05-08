<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class ProductReport extends Component
{
    use WithAlert;

    #[Computed]
    public function products()
    {
        return \App\Models\Product::query()
            ->select('products.id', 'products.name', 'products.code')
            ->selectRaw('COALESCE((SELECT SUM(qty * cost) FROM product_warehouse WHERE product_id = products.id), 0) as inventory_valuation')
            ->selectRaw('COALESCE((SELECT SUM(qty) FROM product_warehouse WHERE product_id = products.id), 0) as current_stock')
            ->selectRaw('COALESCE((SELECT SUM(quantity) FROM sale_details WHERE product_id = products.id), 0) as total_sold')
            ->paginate(10);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.product-report');
    }
}
