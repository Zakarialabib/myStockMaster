# Livewire v4 Reports Refactor Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refactor the Livewire Report components to leverage `#[Computed]`, `#[Url(history: true)]`, and remove redundant `render()` calls from actions.

**Architecture:** 
- Extract heavy database queries out of `render()` into `#[Computed]` methods.
- Apply `#[Url(history: true)]` to all filterable report state parameters (dates, IDs, statuses).
- Remove `$this->render();` calls from `generateReport()` action methods.
- Update corresponding blade views to consume `$this->property` instead of `$property`.

**Tech Stack:** Laravel, Livewire v4

---

### Task 1: Refactor SalesReport

**Files:**
- Modify: `app/Livewire/Reports/SalesReport.php`
- Modify: `resources/views/livewire/reports/sales-report.blade.php`

- [ ] **Step 1: Refactor component logic**

Move `render()` query into `#[Computed] public function sales()`. Remove `$this->render()` from `generateReport()`. Apply `#[Url(history: true)]` to filter variables.

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
class SalesReport extends Component
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
    public ?string $sale_status = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->customer_id = $this->customer_id ?? '';
        $this->sale_status = $this->sale_status ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()->select(['id', 'name'])->get();
    }

    #[Computed]
    public function sales()
    {
        return Sale::with('customer')->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->sale_status, fn ($q) => $q->where('status', $this->sale_status))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.sales-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
```

- [ ] **Step 2: Update blade template**

Replace `$sales` with `$this->sales` in `resources/views/livewire/reports/sales-report.blade.php`.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/SalesReport.php resources/views/livewire/reports/sales-report.blade.php
git commit -m "refactor(livewire): migrate SalesReport to v4 computed properties and URLs"
```

### Task 2: Refactor PurchasesReport

**Files:**
- Modify: `app/Livewire/Reports/PurchasesReport.php`
- Modify: `resources/views/livewire/reports/purchases-report.blade.php`

- [ ] **Step 1: Refactor component logic**

Move `render()` query into `#[Computed] public function purchases()`. Apply `#[Url(history: true)]` to `$start_date`, `$end_date`, `$supplier_id`, `$purchase_status`, `$payment_status`.

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PurchasesReport extends Component
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
    public ?string $supplier_id = null;

    #[Url(history: true)]
    public ?string $purchase_status = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->supplier_id = $this->supplier_id ?? '';
        $this->purchase_status = $this->purchase_status ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->select(['id', 'name'])->get();
    }

    #[Computed]
    public function purchases()
    {
        return Purchase::query()
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, fn ($query) => $query->where('supplier_id', $this->supplier_id))
            ->when($this->purchase_status, fn ($query) => $query->where('status', $this->purchase_status))
            ->when($this->payment_status, fn ($query) => $query->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.purchases-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
```

- [ ] **Step 2: Update blade template**

Replace `$purchases` with `$this->purchases` in `resources/views/livewire/reports/purchases-report.blade.php`.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/PurchasesReport.php resources/views/livewire/reports/purchases-report.blade.php
git commit -m "refactor(livewire): migrate PurchasesReport to v4 computed properties and URLs"
```

### Task 3: Refactor Return Reports

**Files:**
- Modify: `app/Livewire/Reports/SalesReturnReport.php`
- Modify: `resources/views/livewire/reports/sales-return-report.blade.php`
- Modify: `app/Livewire/Reports/PurchasesReturnReport.php`
- Modify: `resources/views/livewire/reports/purchases-return-report.blade.php`

- [ ] **Step 1: Apply changes to both return components similarly**

Extract logic into `#[Computed] public function saleReturns()` and `#[Computed] public function purchaseReturns()`. Add `#[Url(history: true)]` to their properties. Remove `$this->render()` calls in `generateReport()`.

- [ ] **Step 2: Update blade templates**

Consume `$this->saleReturns` and `$this->purchaseReturns` respectively.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/SalesReturnReport.php app/Livewire/Reports/PurchasesReturnReport.php resources/views/livewire/reports/*return-report.blade.php
git commit -m "refactor(livewire): migrate Return Reports to v4 computed properties and URLs"
```

### Task 4: Refactor PaymentsReport

**Files:**
- Modify: `app/Livewire/Reports/PaymentsReport.php`
- Modify: `resources/views/livewire/reports/payments-report.blade.php`

- [ ] **Step 1: Refactor component**

Extract logic into `#[Computed] public function information()`. Add `#[Url(history: true)]` to `$start_date`, `$end_date`, `$payment_method`.

- [ ] **Step 2: Update blade**

Consume `$this->information` instead of `$information`.

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/PaymentsReport.php resources/views/livewire/reports/payments-report.blade.php
git commit -m "refactor(livewire): migrate PaymentsReport to v4 computed properties and URLs"
```