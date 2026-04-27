# Livewire v4 Data Enhancements Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Enhance PaymentsReport, ProductReport, and ProfitLossReport with advanced data logic including dynamic cash flow tracking, inventory valuation, turnover ratio, and profit margins.

**Architecture:** 
- Add missing Eloquent relationships for accurate eager-loading.
- Use `#[Computed]` methods for aggregated data.
- Integrate new metrics into Blade views without compromising existing UI.

**Tech Stack:** Laravel, Livewire v4, Tailwind CSS

---

### Task 1: PaymentsReport Enhancements

**Files:**
- Modify: `app/Models/SalePayment.php`
- Modify: `app/Models/PurchasePayment.php`
- Modify: `app/Models/SaleReturnPayment.php`
- Modify: `app/Models/PurchaseReturnPayment.php`
- Modify: `app/Livewire/Reports/PaymentsReport.php`
- Modify: `resources/views/livewire/reports/payments-report.blade.php`

- [ ] **Step 1: Add missing Eloquent relationships**

Add `user()` and `cashRegister()` to all 4 payment models:
```php
use App\Models\User;
use App\Models\CashRegister;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
```

- [ ] **Step 2: Enhance PaymentsReport logic**

Add the `cashFlowSummary` method to `PaymentsReport.php` and eager load `user` and `cashRegister` in `information()`.

```php
    #[Computed]
    public function cashFlowSummary()
    {
        if (! $this->payments) {
            return collect();
        }

        $query = match ($this->payments) {
            'sale' => \App\Models\SalePayment::query(),
            'sale_return' => \App\Models\SaleReturnPayment::query(),
            'purchase' => \App\Models\PurchasePayment::query(),
            'purchase_return' => \App\Models\PurchaseReturnPayment::query(),
            default => null,
        };

        if ($query) {
            return $query->whereDate('date', '>=', $this->start_date)
                ->whereDate('date', '<=', $this->end_date)
                ->selectRaw('payment_method, SUM(amount) as total_amount')
                ->groupBy('payment_method')
                ->get();
        }

        return collect();
    }
```

Update `information()` to eager load:
```php
        $query = match ($this->payments) {
            'sale' => \App\Models\SalePayment::query()->with(['sale', 'user', 'cashRegister']),
            'sale_return' => \App\Models\SaleReturnPayment::query()->with(['saleReturn', 'user', 'cashRegister']),
            'purchase' => \App\Models\PurchasePayment::query()->with(['purchase', 'user', 'cashRegister']),
            'purchase_return' => \App\Models\PurchaseReturnPayment::query()->with(['purchaseReturn', 'user', 'cashRegister']),
            default => null,
        };
```

- [ ] **Step 3: Update blade view**

Add the cash flow summary above the table in `payments-report.blade.php`:
```blade
        @if($this->cashFlowSummary->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                @foreach($this->cashFlowSummary as $summary)
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <p class="text-sm text-gray-500 font-medium">{{ \App\Enums\PaymentMethod::getLabel($summary->payment_method) ?? $summary->payment_method }}</p>
                        <p class="text-xl font-bold text-gray-800">{{ format_currency($summary->total_amount) }}</p>
                    </div>
                @endforeach
            </div>
        @endif
```
And add User and Cash Register columns to the table.

- [ ] **Step 4: Commit**

```bash
git commit -a -m "feat(reports): enhance PaymentsReport with cash flow summary and traceability"
```

### Task 2: ProductReport Enhancements

**Files:**
- Modify: `app/Livewire/Reports/ProductReport.php`
- Modify: `resources/views/livewire/reports/product-report.blade.php`

- [ ] **Step 1: Add Component Logic**

Add `products` computed property in `ProductReport.php`:
```php
    #[Computed]
    public function products()
    {
        return \App\Models\Product::query()
            ->select('products.id', 'products.name', 'products.code')
            ->selectRaw('COALESCE((SELECT SUM(qty * cost) FROM product_warehouse WHERE product_id = products.id), 0) as inventory_valuation')
            ->selectRaw('COALESCE((SELECT SUM(qty) FROM product_warehouse WHERE product_id = products.id), 0) as current_stock')
            ->selectRaw('COALESCE((SELECT SUM(quantity) FROM sale_details WHERE product_id = products.id), 0) as total_sold')
            ->paginate(10);
    }
```

- [ ] **Step 2: Update blade view**

Add the `<x-page-container>` layout and the data table calculating Turnover Ratio.
```blade
<div>
    <x-page-container title="{{ __('Product Report') }}" :breadcrumbs="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => __('Reports'), 'url' => '#'],
        ['label' => __('Product Report'), 'url' => '#']
    ]" :show-filters="false">
        
        <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md dark:bg-gray-800 dark:border-blue-500">
            <div class="flex items-start">
                <div class="shrink-0"><i class="fas fa-info-circle text-blue-400"></i></div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>{{ __('How to get the most from this report:') }}</strong> 
                        {{ __('Analyze inventory valuation (total capital tied up in stock) and turnover ratios. A low turnover ratio may indicate dead stock, while a high ratio indicates fast-moving goods.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Product Name') }}</x-table.th>
                    <x-table.th>{{ __('Current Stock') }}</x-table.th>
                    <x-table.th>{{ __('Total Sold') }}</x-table.th>
                    <x-table.th>{{ __('Inventory Valuation') }}</x-table.th>
                    <x-table.th>{{ __('Turnover Ratio') }}</x-table.th>
                </x-slot>
                <x-table.tbody>
                    @foreach($this->products as $product)
                        @php
                            $averageStock = ($product->current_stock + $product->total_sold) / 2;
                            $turnoverRatio = $averageStock > 0 ? ($product->total_sold / $averageStock) : 0;
                        @endphp
                        <x-table.tr>
                            <x-table.td>{{ $product->name }} ({{ $product->code }})</x-table.td>
                            <x-table.td>{{ $product->current_stock }}</x-table.td>
                            <x-table.td>{{ $product->total_sold }}</x-table.td>
                            <x-table.td>{{ format_currency($product->inventory_valuation) }}</x-table.td>
                            <x-table.td>{{ number_format($turnoverRatio, 2) }}</x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>
            <div class="p-4">{{ $this->products->links() }}</div>
        </div>
    </x-page-container>
</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/ProductReport.php resources/views/livewire/reports/product-report.blade.php
git commit -m "feat(reports): implement ProductReport with inventory valuation and turnover ratio"
```

### Task 3: ProfitLossReport Enhancements

**Files:**
- Modify: `app/Livewire/Reports/ProfitLossReport.php`
- Modify: `resources/views/livewire/reports/profit-loss-report.blade.php`

- [ ] **Step 1: Add Margin Logic**

In `ProfitLossReport.php`, add:
```php
    public float|int $net_profit_amount = 0;
    public float|int $gross_profit_margin = 0;
    public float|int $net_profit_margin = 0;
```
In `setValues()`, calculate them:
```php
        $this->profit_amount = $this->calculateProfit();
        $this->net_profit_amount = $this->profit_amount - $this->expenses_amount;
        
        $revenue = $this->sales_amount - $this->sale_returns_amount;

        $this->gross_profit_margin = $revenue > 0 ? ($this->profit_amount / $revenue) * 100 : 0;
        $this->net_profit_margin = $revenue > 0 ? ($this->net_profit_amount / $revenue) * 100 : 0;
```

- [ ] **Step 2: Update blade view**

Add the 3 new cards in the grid:
```blade
        {{-- Net Profit --}}
        <x-card-tooltip icon="bi bi-cash-coin" color="emerald">
            <span class="text-2xl">{{ format_currency($net_profit_amount) }}</span>
            <p>{{ __('Net Profit') }}</p>
            <x-slot name="content">
                <p class="text-sm">{{ __('Gross profit minus total expenses.') }}</p>
            </x-slot>
        </x-card-tooltip>

        {{-- Gross Profit Margin --}}
        <x-card-tooltip icon="bi bi-percent" color="teal">
            <span class="text-2xl">{{ number_format($gross_profit_margin, 2) }}%</span>
            <p>{{ __('Gross Profit Margin') }}</p>
            <x-slot name="content">
                <p class="text-sm">{{ __('(Gross Profit / Net Revenue) * 100') }}</p>
            </x-slot>
        </x-card-tooltip>

        {{-- Net Profit Margin --}}
        <x-card-tooltip icon="bi bi-percent" color="cyan">
            <span class="text-2xl">{{ number_format($net_profit_margin, 2) }}%</span>
            <p>{{ __('Net Profit Margin') }}</p>
            <x-slot name="content">
                <p class="text-sm">{{ __('(Net Profit / Net Revenue) * 100') }}</p>
            </x-slot>
        </x-card-tooltip>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Reports/ProfitLossReport.php resources/views/livewire/reports/profit-loss-report.blade.php
git commit -m "feat(reports): add net profit and margins to ProfitLossReport"
```