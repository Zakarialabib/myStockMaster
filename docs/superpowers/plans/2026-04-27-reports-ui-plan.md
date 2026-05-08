# Livewire v4 Reports UI Modernization Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Modernize the Blade templates for the Reports namespace to match the `Products/Index` component's UI, using `<x-page-container>`, breadcrumbs, an information alert, and clean filter grids.

**Architecture:** 
- Use `<x-page-container>` for headers and breadcrumbs.
- Place filters inside `<x-slot name="filters">`.
- Add a helpful information alert section.
- Wrap tables in rounded, shadowed containers.

**Tech Stack:** Laravel, Livewire v4, Tailwind CSS

---

### Task 1: Modernize Purchases Report UI

**Files:**
- Modify: `resources/views/livewire/reports/purchases-report.blade.php`

- [ ] **Step 1: Replace old layout with modern page container and info alert**

Replace the top-level `<div>` and `<div class="card">` logic with:

```blade
<div>
    <x-page-container title="{{ __('Purchases Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Purchases Report'), 'url' => '#']
    ]" :show-filters="true">

        <x-slot name="filters">
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>{{ __('How to get the most from this report:') }}</strong> 
                            {{ __('Use the date filters to narrow down your purchases. Filter by specific suppliers or payment statuses to easily track outstanding balances and order progress.') }}
                        </p>
                    </div>
                </div>
            </div>

            <form wire:submit="generateReport">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <div>
                        <x-label for="start_date" :value="__('Start Date')" />
                        <x-input wire:model="start_date" type="date" id="start_date" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="end_date" :value="__('End Date')" />
                        <x-input wire:model="end_date" type="date" id="end_date" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="supplier_id" :value="__('Supplier')" />
                        <x-select-list :options="$this->suppliers" wire:model.live="supplier_id" id="supplier_id" />
                    </div>
                    <div>
                        <x-label for="purchase_status" :value="__('Status')" />
                        <x-select wire:model="purchase_status" id="purchase_status" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach (\App\Enums\PurchaseStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ __($status->name) }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <x-label for="payment_status" :value="__('Payment Status')" />
                        <x-select wire:model="payment_status" id="payment_status" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">{{ __('All Payment Statuses') }}</option>
                            @foreach (\App\Enums\PaymentStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ __($status->name) }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button type="submit" primary>{{ __('Filter Report') }}</x-button>
                </div>
            </form>
        </x-slot>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
            <!-- Existing x-table code goes here (keep the table the same) -->
```

- [ ] **Step 2: Close the tags at the bottom**

Ensure the table pagination uses `$this->purchases->links()` and the tags are properly closed:
```blade
            <div class="p-4">
                {{ $this->purchases->links() }}
            </div>
        </div>
    </x-page-container>
</div>
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/livewire/reports/purchases-report.blade.php
git commit -m "style(reports): modernize purchases report UI with page container and breadcrumbs"
```

### Task 2: Modernize Sales Report UI

**Files:**
- Modify: `resources/views/livewire/reports/sales-report.blade.php`

- [ ] **Step 1: Apply the same layout to Sales Report**

Replace the top-level structure with `<x-page-container>` and `<x-slot name="filters">`. Update the info alert text to reference "sales" and "customers" instead of "purchases" and "suppliers". Update the filter grid to use `customer_id` and `sale_status`.

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/reports/sales-report.blade.php
git commit -m "style(reports): modernize sales report UI with page container and breadcrumbs"
```

### Task 3: Modernize Returns & Payments UI

**Files:**
- Modify: `resources/views/livewire/reports/sales-return-report.blade.php`
- Modify: `resources/views/livewire/reports/purchases-return-report.blade.php`
- Modify: `resources/views/livewire/reports/payments-report.blade.php`

- [ ] **Step 1: Apply the `<x-page-container>` layout to the remaining reports**

Ensure the grids adapt correctly to the number of filters available on each report. Keep the existing table structure but wrap it in the modern `bg-white rounded-xl shadow-sm border` container.

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/reports/sales-return-report.blade.php resources/views/livewire/reports/purchases-return-report.blade.php resources/views/livewire/reports/payments-report.blade.php
git commit -m "style(reports): modernize returns and payments report UI"
```