<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;

class StockAlertReport extends Component
{
    use WithPagination;

    public function getProductAlertProperty()
    {
        return Product::select('id', 'name', 'quantity', 'stock_alert', 'code')
            ->whereColumn('quantity', '<=', 'stock_alert')
            ->paginate();
    }

    public function render()
    {
        return view('livewire.reports.stock-alert-report');
    }
}
