<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Layout('layouts.app')]
#[Lazy]
class Dashboard extends Component
{
    use WithAlert;

    public string $startDate;

    public string $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function placeholder()
    {
        return view('livewire.placeholders.dashboard');
    }

    #[Computed]
    public function categoriesCount()
    {
        return Cache::flexible('dashboard_categories_count', [300, 600], function () {
            return Category::count('id');
        });
    }

    #[Computed]
    public function productCount()
    {
        $key = "dashboard_product_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return Product::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        });
    }

    #[Computed]
    public function salesCount()
    {
        $key = "dashboard_sales_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return Sale::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        });
    }

    #[Computed]
    public function supplierCount()
    {
        $key = "dashboard_supplier_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return Supplier::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        });
    }

    #[Computed]
    public function customerCount()
    {
        $key = "dashboard_customer_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return Customer::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        });
    }

    #[Computed]
    public function purchasesCount()
    {
        $key = "dashboard_purchases_count_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return Purchase::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        });
    }

    #[Computed]
    public function bestSellingProduct()
    {
        $key = "dashboard_best_selling_product_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return \App\Models\SaleDetails::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('sale', function ($query) {
                    $query->whereBetween('date', [$this->startDate, $this->endDate]);
                })
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->with('product')
                ->first()?->product->name ?? 'N/A';
        });
    }

    #[Computed]
    public function numberOfProductsSold()
    {
        $key = "dashboard_products_sold_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return \App\Models\SaleDetails::whereHas('sale', function ($query) {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            })->sum('quantity');
        });
    }

    #[Computed]
    public function averagePurchaseReturnAmount()
    {
        $key = "dashboard_avg_purchase_return_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return \App\Models\PurchaseReturn::whereBetween('date', [$this->startDate, $this->endDate])
                ->avg('total_amount') ?? 0;
        });
    }

    #[Computed]
    public function commonReturnReason()
    {
        $key = "dashboard_common_return_reason_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return \App\Models\PurchaseReturn::select('note', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->whereBetween('date', [$this->startDate, $this->endDate])
                ->whereNotNull('note')
                ->groupBy('note')
                ->orderByDesc('total')
                ->value('note') ?? 'N/A';
        });
    }

    #[Computed]
    public function averagePaymentReceivedPerSale()
    {
        $key = "dashboard_avg_payment_sale_{$this->startDate}_{$this->endDate}";

        return Cache::flexible($key, [60, 120], function () {
            return Sale::whereBetween('date', [$this->startDate, $this->endDate])
                ->avg('paid_amount') ?? 0;
        });
    }

    #[Computed]
    public function significantPaymentChanges()
    {
        return 0; // Placeholder for now
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'categoriesCount' => $this->categoriesCount,
            'productCount' => $this->productCount,
            'salesCount' => $this->salesCount,
            'supplierCount' => $this->supplierCount,
            'customerCount' => $this->customerCount,
            'purchasesCount' => $this->purchasesCount,
            'best_selling_product' => $this->bestSellingProduct,
            'number_of_products_sold' => $this->numberOfProductsSold,
            'average_purchase_return_amount' => $this->averagePurchaseReturnAmount,
            'common_return_reason' => $this->commonReturnReason,
            'average_payment_received_per_sale' => $this->averagePaymentReceivedPerSale,
            'significant_payment_changes' => $this->significantPaymentChanges,
        ]);
    }
}
