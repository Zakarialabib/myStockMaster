<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class StockAlertReport extends Component
{
    use WithPagination;

    public function getProductAlertProperty()
    {
        return Product::select(['id', 'name', 'quantity', 'stock_alert', 'code'])
            ->whereColumn('quantity', '<=', 'stock_alert')
            ->paginate();
    }

    public function render()
    {
        return view('livewire.reports.stock-alert-report');
    }
}
