<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Livewire\Utils\Datatable;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class Details extends Component
{
    use Datatable;
    use WithAlert;

    public string $model = Customer::class;

    #[Locked]
    public mixed $customer_id;

    public mixed $customer;

    public mixed $sales;

    public mixed $warehouse_id;

    public function mount(int|string $id): void
    {
        $this->customer = Customer::query()->where('id', $id)->firstOrFail();
        $this->customer_id = $this->customer->id;
    }

    #[Computed]
    public function sales()
    {
        $query = Sale::query()->where('customer_id', $this->customer_id)
            ->with('customer')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function customerPayments()
    {
        $query = Sale::query()->where('customer_id', $this->customer_id)
            ->with('salepayments.sale')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function totalSales(): int|float
    {
        return $this->customerSum('total_amount');
    }

    #[Computed]
    public function totalSaleReturns(): int|float
    {
        return SaleReturn::query()->whereBelongsTo($this->customer)
            ->completed()->sum('total_amount') / 100;
    }

    #[Computed]
    public function totalPayments(): int|float
    {
        return $this->customerSum('paid_amount') / 100;
    }

    // total due amount
    #[Computed]
    public function totalDue(): int|float
    {
        return $this->customerSum('due_amount') / 100;
    }

    #[Computed]
    public function profit(): int|float
    {
        // Step 1: Calculate total sales revenue for completed sales
        $salesTotal = Sale::query()->where('customer_id', $this->customer_id)
            ->completed()
            ->sum('total_amount') / 100;

        // Step 2: Calculate total sales returns
        $saleReturnsTotal = SaleReturn::query()->where('customer_id', $this->customer_id)
            ->completed()
            ->sum('total_amount') / 100;

        // Step 3: Calculate the total product cost from the pivot table
        $productCosts = 0;

        foreach ($this->sales as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                // Assuming you have a warehouses relationship defined on the Product model
                $productWarehouse = $saleDetail->product->warehouses->where('warehouse_id', $this->warehouse_id)->first();

                if ($productWarehouse) {
                    $productCosts += $productWarehouse->cost * $saleDetail->quantity;
                }
            }
        }

        // Step 4: Calculate profit
        $profit = ($salesTotal - $saleReturnsTotal) - $productCosts;

        return $profit;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.customers.details');
    }

    private function customerSum(string $field): int|float
    {
        return Sale::query()->whereBelongsTo($this->customer)->sum($field) / 100;
    }
}
