<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class WarehouseReport extends Component
{
    use WithAlert;
    use WithPagination;

    public mixed $warehouses;

    #[Url(history: true)]
    public ?int $warehouse_id = null;

    #[Url(history: true)]
    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public mixed $start_date;

    #[Url(history: true)]
    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public mixed $end_date;

    public function mount(): void
    {
        $this->warehouses = Warehouse::query()->select(['id', 'name'])->get();

        if (! $this->start_date) {
            $this->start_date = today()->subDays(30)->format('Y-m-d');
        }
        if (! $this->end_date) {
            $this->end_date = today()->format('Y-m-d');
        }
    }

    #[Computed]
    public function purchases()
    {
        return Purchase::where('warehouse_id', $this->warehouse_id)
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->with(['supplier', 'purchaseDetails.product'])
            ->orderBy('date', 'desc')
            ->paginate(5, ['*'], 'purchasesPage');
    }

    #[Computed]
    public function sales()
    {
        return Sale::where('warehouse_id', $this->warehouse_id)
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->with(['customer', 'saleDetails.product'])
            ->orderBy('date', 'desc')
            ->paginate(5, ['*'], 'salesPage');
    }

    #[Computed]
    public function expenses()
    {
        return Expense::where('warehouse_id', $this->warehouse_id)
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->with('category')
            ->orderBy('date', 'desc')
            ->paginate(5, ['*'], 'expensesPage');
    }

    #[Computed]
    public function stockValue()
    {
        if (! $this->warehouse_id) {
            return 0;
        }

        return \App\Models\ProductWarehouse::where('warehouse_id', $this->warehouse_id)
            ->sum(\Illuminate\Support\Facades\DB::raw('qty * cost'));
    }

    #[Computed]
    public function totalSales()
    {
        return Sale::where('warehouse_id', $this->warehouse_id)
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->where('status', \App\Enums\SaleStatus::COMPLETED)
            ->sum('total_amount');
    }

    #[Computed]
    public function totalPurchases()
    {
        return Purchase::where('warehouse_id', $this->warehouse_id)
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->where('status', \App\Enums\PurchaseStatus::COMPLETED)
            ->sum('total_amount');
    }

    #[Computed]
    public function totalExpenses()
    {
        return Expense::where('warehouse_id', $this->warehouse_id)
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->sum('amount');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.warehouse-report');
    }
}
