<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $startDate;

    public $endDate;

    public $categoriesCount;

    public $productCount;

    public $salesCount;

    public $supplierCount;

    public $customerCount;

    public $purchaseCount;

    public $purchasesCount;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        $this->categoriesCount = Category::count('id');

        $this->productCount = Product::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->supplierCount = Supplier::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->customerCount = Customer::whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $this->salesCount = Sale::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        $this->purchasesCount = Purchase::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        // $this->best_selling_product = $this->calculateBestSellingProduct();
        // $this->number_of_products_sold = $this->calculateNumberOfProductsSold();
        // $this->average_purchase_return_amount = $this->calculateAveragePurchaseReturnAmount();
        // $this->common_return_reason = $this->findCommonReturnReason();
        // $this->average_payment_received_per_sale = $this->calculateAveragePaymentReceivedPerSale();
        // $this->significant_payment_changes = $this->detectSignificantPaymentChanges();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
