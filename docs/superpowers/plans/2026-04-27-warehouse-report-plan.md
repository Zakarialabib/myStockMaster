# Livewire v4 Warehouse Report Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement the "Comprehensive Branch Dashboard" for the Warehouse Report, fixing N+1 queries and adding financial valuation metrics.

**Architecture:** 
- Delete the legacy `warehouseReport()` method that manually loops through collections.
- Use `#[Computed]` properties to eager load `saleDetails` and `purchaseDetails`.
- Add `#[Computed]` aggregate methods for KPI cards (Valuation, Total Sales, Total Purchases, Total Expenses).
- Build out the empty blade stub using the standard `<x-page-container>` layout with KPI cards and tabbed/stacked data tables.

**Tech Stack:** Laravel, Livewire v4, Tailwind CSS

---

### Task 1: Refactor WarehouseReport.php Logic

**Files:**
- Modify: `app/Livewire/Reports/WarehouseReport.php`

- [ ] **Step 1: Clean up legacy properties and methods**

Remove the `public $productPurchase`, `$productSale`, `$productQuotation` arrays and delete the `warehouseReport()` method completely.

- [ ] **Step 2: Add URL history to filters**

Add `#[Url(history: true)]` to `$warehouse_id`, `$start_date`, and `$end_date`.

- [ ] **Step 3: Enhance Computed Properties with Eager Loading**

Update the existing computed properties to eager load their details.

```php
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
```

- [ ] **Step 4: Add KPI Computed Properties**

Add methods to calculate the totals for the dashboard cards.

```php
    #[Computed]
    public function stockValue()
    {
        if (!$this->warehouse_id) return 0;
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
```

- [ ] **Step 5: Commit**

```bash
git add app/Livewire/Reports/WarehouseReport.php
git commit -m "refactor(reports): fix N+1 queries and add KPI metrics to WarehouseReport"
```

### Task 2: Build Warehouse Dashboard UI

**Files:**
- Modify: `resources/views/livewire/reports/warehouse-report.blade.php`

- [ ] **Step 1: Scaffold Page Container and Filters**

Replace the empty stub with the `<x-page-container>` layout and the filter grid.

```blade
<div>
    <x-page-container title="{{ __('Warehouse Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Warehouse Report'), 'url' => '#']
    ]" :show-filters="true">

        <x-slot name="filters">
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
                <div class="flex items-start">
                    <div class="shrink-0"><i class="fas fa-info-circle text-blue-400"></i></div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>{{ __('Comprehensive Branch Dashboard:') }}</strong> 
                            {{ __('Select a warehouse to view its total inventory valuation and financial performance over the selected period.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <x-label for="warehouse_id" :value="__('Warehouse')" />
                    <x-select-list :options="$this->warehouses" wire:model.live="warehouse_id" id="warehouse_id" />
                </div>
                <div>
                    <x-label for="start_date" :value="__('Start Date')" />
                    <x-input wire:model.live="start_date" type="date" id="start_date" />
                </div>
                <div>
                    <x-label for="end_date" :value="__('End Date')" />
                    <x-input wire:model.live="end_date" type="date" id="end_date" />
                </div>
            </div>
        </x-slot>

        <!-- KPIs and Tables will go here -->
```

- [ ] **Step 2: Add KPI Cards Row**

```blade
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card-tooltip icon="bi bi-box-seam" color="indigo">
                <span class="text-2xl">{{ format_currency($this->stockValue) }}</span>
                <p>{{ __('Total Inventory Value') }}</p>
            </x-card-tooltip>

            <x-card-tooltip icon="bi bi-graph-up-arrow" color="emerald">
                <span class="text-2xl">{{ format_currency($this->totalSales) }}</span>
                <p>{{ __('Total Sales') }}</p>
            </x-card-tooltip>

            <x-card-tooltip icon="bi bi-cart-check" color="blue">
                <span class="text-2xl">{{ format_currency($this->totalPurchases) }}</span>
                <p>{{ __('Total Purchases') }}</p>
            </x-card-tooltip>

            <x-card-tooltip icon="bi bi-receipt" color="rose">
                <span class="text-2xl">{{ format_currency($this->totalExpenses) }}</span>
                <p>{{ __('Total Expenses') }}</p>
            </x-card-tooltip>
        </div>
```

- [ ] **Step 3: Add Stacked Tables for Transactions**

Add the tables to display the eager-loaded data.

```blade
        <div class="space-y-6">
            <!-- Sales Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Sales') }}</h3>
                </div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Reference') }}</x-table.th>
                        <x-table.th>{{ __('Customer') }}</x-table.th>
                        <x-table.th>{{ __('Status') }}</x-table.th>
                        <x-table.th>{{ __('Total') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @forelse($this->sales as $sale)
                            <x-table.tr>
                                <x-table.td>{{ $sale->date->format('Y-m-d') }}</x-table.td>
                                <x-table.td>{{ $sale->reference }}</x-table.td>
                                <x-table.td>{{ $sale->customer->name }}</x-table.td>
                                <x-table.td>
                                    <x-badge :type="$sale->status->getBadgeType()">{{ $sale->status->getName() }}</x-badge>
                                </x-table.td>
                                <x-table.td>{{ format_currency($sale->total_amount) }}</x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="5" class="text-center">{{ __('No sales found.') }}</x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>
                <div class="p-4">{{ $this->sales->links() }}</div>
            </div>

            <!-- Purchases Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Purchases') }}</h3>
                </div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Date') }}</x-table.th>
                        <x-table.th>{{ __('Reference') }}</x-table.th>
                        <x-table.th>{{ __('Supplier') }}</x-table.th>
                        <x-table.th>{{ __('Status') }}</x-table.th>
                        <x-table.th>{{ __('Total') }}</x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @forelse($this->purchases as $purchase)
                            <x-table.tr>
                                <x-table.td>{{ $purchase->date->format('Y-m-d') }}</x-table.td>
                                <x-table.td>{{ $purchase->reference }}</x-table.td>
                                <x-table.td>{{ $purchase->supplier->name }}</x-table.td>
                                <x-table.td>
                                    <x-badge :type="$purchase->status->getBadgeType()">{{ $purchase->status->getName() }}</x-badge>
                                </x-table.td>
                                <x-table.td>{{ format_currency($purchase->total_amount) }}</x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="5" class="text-center">{{ __('No purchases found.') }}</x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>
                <div class="p-4">{{ $this->purchases->links() }}</div>
            </div>
        </div>

    </x-page-container>
</div>
```

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/reports/warehouse-report.blade.php
git commit -m "feat(reports): build comprehensive Warehouse Dashboard UI"
```