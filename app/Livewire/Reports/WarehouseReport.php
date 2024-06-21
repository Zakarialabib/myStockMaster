<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Quotation;
use App\Models\Warehouse;
use App\Models\QuotationDetails;
use App\Models\Sale;
use App\Models\SaleDetails;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class WarehouseReport extends Component
{
    public $warehouses;

    public $warehouse_id;

    public $purchases;

    public $sales;

    public $quotations;

    public $productPurchase;

    public $productSale;

    public $productQuotation;

    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public $start_date;

    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public $end_date;

    public function mount(): void
    {
        $this->warehouses = Warehouse::select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
    }

    #[Computed]
    public function purchases()
    {
        return Purchase::where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function sales()
    {
        return Sale::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function quotations()
    {
        return Quotation::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function expenses()
    {
        return Expense::with('category')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function warehouseReport(): void
    {
        $this->productPurchase = $this->purchases->map(static fn ($purchase) => PurchaseDetail::where('purchase_id', $purchase->id)->get());

        $this->productSale = $this->sales->map(static fn ($sale) => SaleDetails::where('sale_id', $sale->id)->get());

        $this->productQuotation = $this->quotations->map(static fn ($quotation) => QuotationDetails::where('quotation_id', $quotation->id)->get());
    }

    public function render()
    {
        return view('livewire.reports.warehouse-report');
    }
}
