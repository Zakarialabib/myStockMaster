# Livewire v4 Enhancements: Reports Refactor Design

**Date**: 2026-04-27
**Status**: Approved
**Branch**: livewire-v4-enhancements

## 1. Executive Summary
This spec outlines the refactoring of the `app/Livewire/Reports` namespace to adhere to the Livewire v4 patterns defined in the previous phases. Specifically, we will extract complex query logic from the `render()` methods into explicitly defined `#[Computed]` properties, implement `#[Url]` for bookmarkable report states, and remove redundant `generateReport()` methods that merely re-call `$this->render()`.

## 2. Architecture & Patterns

### 2.1 Computed Properties vs Render
Currently, many report components run their primary database queries directly inside `render()`. 
**New Approach**:
- `render()` should remain lean and only return the view.
- Heavy data fetching (e.g., fetching Sales, Purchases, Returns) will be moved to `#[Computed]` methods (e.g., `#[Computed] public function sales()`).
- The blade templates will be updated to consume the computed properties (e.g., `$this->sales` instead of `$sales`).

### 2.2 Shareable State (URL Attributes)
Reports are highly filter-driven. The filtering parameters should be bookmarkable.
**New Approach**:
- Apply `#[Url(history: true)]` to key filtering parameters across all reports:
  - `$start_date`
  - `$end_date`
  - `$customer_id` / `$supplier_id`
  - `$sale_status` / `$purchase_status`
  - `$payment_status`

### 2.3 Redundant Method Removal
Currently, several reports have a `generateReport()` method that looks like this:
```php
public function generateReport(): void
{
    $this->validate();
    $this->render();
}
```
In Livewire v4, `render()` is called automatically at the end of the lifecycle. Calling it manually inside an action is an anti-pattern.
**New Approach**:
- Change `generateReport()` to only call `$this->validate();` (or rely purely on the updated hooks if applicable).
- Better yet, if the properties are updated via `wire:model.live` or on a simple form submission, just validating them is enough.

## 3. Targeted Components
The following components in `app/Livewire/Reports/` will be updated:
1. `SalesReport`
2. `PurchasesReport`
3. `SalesReturnReport`
4. `PurchasesReturnReport`
5. `PaymentsReport`
6. `CustomersReport` / `SuppliersReport` (if applicable)

## 4. Example Transformation (`SalesReport`)
**Before**:
```php
public function render() {
    $lengthAwarePaginator = Sale::with('customer')->whereDate(...) ... ->paginate(10);
    return view('...', ['sales' => $lengthAwarePaginator]);
}
public function generateReport() { $this->validate(); $this->render(); }
```

**After**:
```php
#[Url(history: true)]
public ?string $start_date = null;
// ... other #[Url] properties

#[Computed]
public function sales()
{
    return Sale::with('customer')
        ->whereDate('date', '>=', $this->start_date)
        ->whereDate('date', '<=', $this->end_date)
        ->when($this->customer_id, fn ($q) => $q->where('customer_id', $this->customer_id))
        ->when($this->sale_status, fn ($q) => $q->where('sale_status', $this->sale_status))
        ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status))
        ->orderBy('date', 'desc')
        ->paginate(10);
}

public function render() {
    return view('livewire.reports.sales-report');
}

public function generateReport() {
    $this->validate();
}
```