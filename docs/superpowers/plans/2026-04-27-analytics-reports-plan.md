# Livewire v4 Analytics & Reports Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement the "Comprehensive Data Fix" for the remaining 4 reports (Customers, Suppliers, Warehouse, StockAlert) by fixing N+1 queries, adding LTV/Debt metrics, and modernizing the UI.

**Architecture:** 
- Extract base queries to `baseQuery()` methods for DRY filtering.
- Add `#[Computed]` properties for aggregate metrics (`ltv`, `totalDueAmount`, `totalPayables`, `stockValue`).
- Refactor `StockAlertReport` to query `ProductWarehouse` instead of `Product` for location-specific alerts.
- Build out empty blade stubs (`SuppliersReport`, `WarehouseReport`) using the standard `<x-page-container>` layout.

**Tech Stack:** Laravel, Livewire v4, Tailwind CSS

---

### Task 1: Enhance CustomersReport (LTV & Debt)

**Files:**
- Modify: `app/Livewire/Reports/CustomersReport.php`
- Modify: `resources/views/livewire/reports/customers-report.blade.php`

- [ ] **Step 1: Update Component Logic**

Add `baseQuery()`, `ltv()`, and `totalDueAmount()`. Update `sales()` to use `baseQuery()`. Add `#[Url(history: true)]` to filters.

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\Sale;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CustomersReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(history: true)]
    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public ?string $start_date = null;

    #[Url(history: true)]
    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public ?string $end_date = null;

    #[Url(history: true)]
    public ?string $customer_id = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->customer_id = $this->customer_id ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()->select(['id', 'name'])->get();
    }

    protected function baseQuery()
    {
        return Sale::query()
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status));
    }

    #[Computed]
    public function ltv()
    {
        return $this->baseQuery()->where('status', \App\Enums\SaleStatus::COMPLETED)->sum('total_amount');
    }

    #[Computed]
    public function totalDueAmount()
    {
        return $this->baseQuery()->sum('due_amount');
    }

    #[Computed]
    public function sales()
    {
        return $this->baseQuery()->with('customer')->orderBy('date', 'desc')->paginate(10);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.customers-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
```

- [ ] **Step 2: Update Blade View**

Wrap in `<x-page-container>`, move filters to `<x-slot name="filters">`, and add KPI cards for LTV and Total Due. Change `$sales` to `$this->sales`.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/CustomersReport.php resources/views/livewire/reports/customers-report.blade.php
git commit -m "feat(reports): enhance CustomersReport with LTV, Total Due Amount, and modern UI"
```

### Task 2: Enhance SuppliersReport (Payables)

**Files:**
- Modify: `app/Livewire/Reports/SuppliersReport.php`
- Modify: `resources/views/livewire/reports/suppliers-report.blade.php`

- [ ] **Step 1: Build Component Logic**

Build out the skeleton using the `Purchase` model. Add `baseQuery()`, `purchases()`, and `totalPayables()`. Add `#[Url(history: true)]` to filters.

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SuppliersReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(history: true)]
    #[Validate('required')]
    #[Validate('date')]
    #[Validate('before:end_date')]
    public ?string $start_date = null;

    #[Url(history: true)]
    #[Validate('required')]
    #[Validate('date')]
    #[Validate('after:start_date')]
    public ?string $end_date = null;

    #[Url(history: true)]
    public ?string $supplier_id = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->supplier_id = $this->supplier_id ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->select(['id', 'name'])->get();
    }

    protected function baseQuery()
    {
        return Purchase::query()
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, fn ($q) => $q->where('supplier_id', $this->supplier_id))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status));
    }

    #[Computed]
    public function totalPayables()
    {
        return $this->baseQuery()->sum('due_amount');
    }

    #[Computed]
    public function purchases()
    {
        return $this->baseQuery()->with('supplier')->orderBy('date', 'desc')->paginate(10);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.suppliers-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
```

- [ ] **Step 2: Build Blade View**

Replace the empty comment with a full `<x-page-container>` layout containing the filters, a KPI card for `Total Payables` (`$this->totalPayables`), and an `<x-table>` iterating over `$this->purchases`.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/SuppliersReport.php resources/views/livewire/reports/suppliers-report.blade.php
git commit -m "feat(reports): build SuppliersReport with Total Payables and modern UI"
```

### Task 3: Enhance StockAlertReport (Warehouse-Specific)

**Files:**
- Modify: `app/Livewire/Reports/StockAlertReport.php`
- Modify: `resources/views/livewire/reports/stock-alert-report.blade.php`

- [ ] **Step 1: Update Component to query `ProductWarehouse`**

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StockAlertReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(history: true)]
    public ?string $warehouse_id = null;

    #[Url(history: true)]
    public int $perPage = 10;

    public function mount(): void
    {
        $this->warehouse_id = $this->warehouse_id ?? '';
    }

    #[Computed]
    public function warehouses()
    {
        return Warehouse::query()->select(['id', 'name'])->get();
    }

    #[Computed]
    public function stockAlert()
    {
        return ProductWarehouse::with(['product', 'warehouse'])
            ->when($this->warehouse_id, fn($q) => $q->where('warehouse_id', $this->warehouse_id))
            ->whereColumn('qty', '<=', 'stock_alert')
            ->paginate($this->perPage);
    }

    public function setThreshold(int $id, int $stockAlert): void
    {
        $productWarehouse = ProductWarehouse::findOrFail($id);
        $productWarehouse->update(['stock_alert' => $stockAlert]);

        $this->alert('success', __('Stock Alert Updated Successfully!'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.stock-alert-report');
    }
}
```

- [ ] **Step 2: Update Blade View**

Wrap in `<x-page-container>`. Add the Warehouse filter dropdown. Update the table loop:
`@foreach($this->stockAlert as $item)`
Row data: `$item->product->code`, `$item->product->name`, `$item->warehouse->name`, `$item->qty`, `$item->stock_alert`.
Update the input binding: `wire:change="setThreshold({{ $item->id }}, $event.target.value)"`.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/StockAlertReport.php resources/views/livewire/reports/stock-alert-report.blade.php
git commit -m "feat(reports): make StockAlertReport warehouse-specific with modern UI"
```