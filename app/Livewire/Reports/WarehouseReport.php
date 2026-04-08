<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class WarehouseReport extends Component
{
    use WithAlert;

    public mixed $warehouses;

    public ?int $warehouse_id = null;

    public mixed $purchases;

    public mixed $sales;

    public mixed $quotations;

    public mixed $productPurchase;

    public mixed $productSale;

    public mixed $productQuotation;

    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public mixed $start_date;

    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public mixed $end_date;

    public function mount(): void
    {
        $this->warehouses = Warehouse::query()->select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
    }

    #[Computed]
    public function purchases()
    {
        return Purchase::query()->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)->latest()
            ->get();
    }

    #[Computed]
    public function sales()
    {
        return Sale::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)->latest()
            ->get();
    }

    #[Computed]
    public function quotations()
    {
        return Quotation::with('customer')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)->latest()
            ->get();
    }

    #[Computed]
    public function expenses()
    {
        return Expense::with('category')
            ->where('warehouse_id', $this->warehouse_id)
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)->latest()
            ->get();
    }

    public function warehouseReport(): void
    {
        $this->productPurchase = $this->purchases->map(static fn ($purchase) => PurchaseDetail::query()->where('purchase_id', $purchase->id)->get());

        $this->productSale = $this->sales->map(static fn ($sale) => SaleDetails::query()->where('sale_id', $sale->id)->get());

        $this->productQuotation = $this->quotations->map(static fn ($quotation) => QuotationDetails::query()->where('quotation_id', $quotation->id)->get());
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.warehouse-report');
    }
}
