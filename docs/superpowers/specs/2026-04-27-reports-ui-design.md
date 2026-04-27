# Livewire v4 Reports UI Modernization & Enums Fix

**Date**: 2026-04-27
**Status**: Approved
**Branch**: livewire-v4-enhancements

## 1. Executive Summary
This design specification focuses on fixing the Enum casting issues across Purchase and Sale relationships and modernizing the UI of the report components to align with the rest of the application (e.g., `Products/Index`). 

## 2. Enums Fixes (Completed & Verified)
We have already verified that the following fixes were implemented:
1. `status` and `payment_status` were added to the `$casts` arrays for `Purchase`, `Sale`, `PurchaseReturn`, and `SaleReturn` models.
2. `getBadgeType()` methods were added or fixed across `SaleStatus`, `PurchaseStatus`, `SaleReturnStatus`, `PurchaseReturnStatus`, and `PaymentStatus`.
3. In `sales-return-report.blade.php`, the invalid `$salereturn?->status` object variable was corrected to `$sale_return->status`.

## 3. UI Modernization Pattern

To bring the reports up to the modern standard seen in `Products/Index`, we will apply the following design pattern to the Blade files:

### 3.1 Breadcrumbs and Header
We will use the `<x-page-container>` wrapper component which provides breadcrumb-styled navigation and headers.

### 3.2 Filters Section
The filters (Dates, Suppliers/Customers, Status) will be placed inside the `<x-slot name="filters">` section of the page container. We will use a clean grid layout.

### 3.3 Information Alert
We will include a small informative alert or section explaining what to expect from the report. For example:
```blade
<div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
    <div class="flex">
        <div class="shrink-0">
            <i class="fas fa-info-circle text-blue-400"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>How to get the most from this report:</strong> Use the date filters to narrow down your transactions. You can also filter by specific customers/suppliers or payment statuses to track outstanding balances.
            </p>
        </div>
    </div>
</div>
```

### 3.4 Table Design
The existing `<x-table>` component will be wrapped in a modern container with a white background, rounded corners, and subtle borders. 
```blade
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
    <x-table>...</x-table>
    <div class="p-4">{{ $this->purchases->links() }}</div>
</div>
```

## 4. Components to Update
1. `resources/views/livewire/reports/purchases-report.blade.php`
2. `resources/views/livewire/reports/sales-report.blade.php`
3. `resources/views/livewire/reports/purchases-return-report.blade.php`
4. `resources/views/livewire/reports/sales-return-report.blade.php`
5. `resources/views/livewire/reports/payments-report.blade.php`