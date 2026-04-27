<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Supplier;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Isolate]
class KpiCards extends Component
{
    public string $startDate = '';

    public string $endDate = '';

    #[Computed]
    public function categoriesCount(): int
    {
        return Cache::flexible('dashboard_categories_count', [300, 600], static fn (): int => Category::query()->count('id'));
    }

    #[Computed]
    public function productCount(): int
    {
        $key = sprintf('dashboard_product_count_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): int => Product::query()->whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function salesCount(): int
    {
        $key = sprintf('dashboard_sales_count_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): int => Sale::query()->whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function supplierCount(): int
    {
        $key = sprintf('dashboard_supplier_count_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): int => Supplier::query()->whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function customerCount(): int
    {
        $key = sprintf('dashboard_customer_count_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): int => Customer::query()->whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function purchasesCount(): int
    {
        $key = sprintf('dashboard_purchases_count_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): int => Purchase::query()->whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function bestSellingProduct(): string
    {
        $key = sprintf('dashboard_best_selling_product_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn(): string => SaleDetails::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('sale', function (\Illuminate\Contracts\Database\Query\Builder $builder): void {
                $builder->whereBetween('date', [$this->startDate, $this->endDate]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product:id,name')
            ->first()?->product->name ?? 'N/A');
    }

    #[Computed]
    public function numberOfProductsSold(): int
    {
        $key = sprintf('dashboard_products_sold_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn(): int => (int) SaleDetails::query()
            ->whereHas('sale', function ($query): void {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            })
            ->sum('quantity'));
    }

    #[Computed]
    public function averagePurchaseReturnAmount(): float
    {
        $key = sprintf('dashboard_avg_purchase_return_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): float => (float) (PurchaseReturn::query()->whereBetween('date', [$this->startDate, $this->endDate])->avg('total_amount') ?? 0));
    }

    #[Computed]
    public function commonReturnReason(): string
    {
        $key = sprintf('dashboard_common_return_reason_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): string => PurchaseReturn::query()
            ->select('note', DB::raw('count(*) as total'))
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->whereNotNull('note')
            ->groupBy('note')
            ->orderByDesc('total')
            ->value('note') ?? 'N/A');
    }

    #[Computed]
    public function averagePaymentReceivedPerSale(): float
    {
        $key = sprintf('dashboard_avg_payment_sale_%s_%s', $this->startDate, $this->endDate);

        return Cache::flexible($key, [60, 120], fn (): float => (float) (Sale::query()->whereBetween('date', [$this->startDate, $this->endDate])->avg('paid_amount') ?? 0));
    }

    #[Computed]
    public function significantPaymentChanges(): int
    {
        return 0;
    }

    public function render(): View|Factory
    {
        return view('livewire.dashboard.kpi-cards');
    }
}
