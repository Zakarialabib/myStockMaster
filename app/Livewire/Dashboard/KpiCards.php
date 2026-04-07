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
use Livewire\Component;

#[Lazy(isolate: false)]
class KpiCards extends Component
{
    public string $startDate = '';

    public string $endDate = '';

    #[Computed]
    public function categoriesCount(): int
    {
        return Cache::flexible('dashboard_categories_count', [300, 600], static fn (): int => Category::count('id'));
    }

    #[Computed]
    public function productCount(): int
    {
        $key = "dashboard_product_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): int => Product::whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function salesCount(): int
    {
        $key = "dashboard_sales_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): int => Sale::whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function supplierCount(): int
    {
        $key = "dashboard_supplier_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): int => Supplier::whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function customerCount(): int
    {
        $key = "dashboard_customer_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): int => Customer::whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function purchasesCount(): int
    {
        $key = "dashboard_purchases_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): int => Purchase::whereBetween('created_at', [$this->startDate, $this->endDate])->count());
    }

    #[Computed]
    public function bestSellingProduct(): string
    {
        $key = "dashboard_best_selling_product_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function (): string {
            return SaleDetails::query()
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('sale', function ($query): void {
                    $query->whereBetween('date', [$this->startDate, $this->endDate]);
                })
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->with('product:id,name')
                ->first()?->product->name ?? 'N/A';
        });
    }

    #[Computed]
    public function numberOfProductsSold(): int
    {
        $key = "dashboard_products_sold_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function (): int {
            return (int) SaleDetails::query()
                ->whereHas('sale', function ($query): void {
                    $query->whereBetween('date', [$this->startDate, $this->endDate]);
                })
                ->sum('quantity');
        });
    }

    #[Computed]
    public function averagePurchaseReturnAmount(): float
    {
        $key = "dashboard_avg_purchase_return_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): float => (float) (PurchaseReturn::whereBetween('date', [$this->startDate, $this->endDate])->avg('total_amount') ?? 0));
    }

    #[Computed]
    public function commonReturnReason(): string
    {
        $key = "dashboard_common_return_reason_{$this->startDate}_{$this->endDate}";

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
        $key = "dashboard_avg_payment_sale_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], fn (): float => (float) (Sale::whereBetween('date', [$this->startDate, $this->endDate])->avg('paid_amount') ?? 0));
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
