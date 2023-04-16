<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Quotation;
use App\Models\Warehouse;
use App\Models\QuotationDetails;
use App\Models\Sale;
use App\Models\SaleDetails;
use Livewire\Component;

class WarehouseReport extends Component
{
    public $warehouses;
    public $warehouse_id;
    public $start_date;
    public $end_date;
    public $purchases;
    public $sales;
    public $quotations;
    public $productPurchase;
    public $productSale;
    public $productQuotation;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
    ];

    public function mount()
    {
        $this->warehouses = Warehouse::select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->warehouse_id = '';
    }

    public function getPurchasesProperty()
    {
        return Purchase::where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSalesProperty()
    {
        return Sale::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getQuotationsProperty()
    {
        return Quotation::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpensesProperty()
    {
        return Expense::with('expenseCategory')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function warehouseReport()
    {
        $this->productPurchase = $this->purchases->map(function ($purchase) {
            return PurchaseDetail::where('purchase_id', $purchase->id)->get();
        });

        $this->productSale = $this->sales->map(function ($sale) {
            return SaleDetails::where('sale_id', $sale->id)->get();
        });

        $this->productQuotation = $this->quotations->map(function ($quotation) {
            return QuotationDetails::where('quotation_id', $quotation->id)->get();
        });
    }

    public function render()
    {
        return view('livewire.reports.warehouse-report');
    }
}
