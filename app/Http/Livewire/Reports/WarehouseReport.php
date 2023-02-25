<?php

namespace App\Http\Livewire\Reports;

use App\Models\PurchaseDetail;
use App\Models\QuotationDetails;
use App\Models\SaleDetails;
use App\Models\Warehouse\Warehouse;
use Livewire\Component;

class WarehouseReport extends Component
{
    public $warehouses;
    public $warehouse_id;
    public $start_date;
    public $end_date;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date' => 'required|date|after:start_date',
    ];

    public function mount()
    {
        $this->warehouses = Warehouse::select('id', 'name')->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->warehouse_id = '';
    }

    public function warehouseReport()
    {
        $purchases = Purchase::where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();

        $sales = Sale::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();

        $quotations = Quotation::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();

        $expenses = Expense::with('expenseCategory')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();

        $productPurchase = $purchases->map(function ($purchase) {
            return PurchaseDetail::where('purchase_id', $purchase->id)->get();
        });

        $productSale = $sales->map(function ($sale) {
            return SaleDetails::where('sale_id', $sale->id)->get();
        });

        $productQuotation = $quotations->map(function ($quotation) {
            return QuotationDetails::where('quotation_id', $quotation->id)->get();
        });
    }

    public function render()
    {
        return view('livewire.reports.warehouse-report');
    }
}
