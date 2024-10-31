<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Livewire\Utils\Datatable;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleReturn;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

#[Layout('layouts.app')]
class Details extends Component
{
    use Datatable;

    public $model = Customer::class;

    #[Locked]
    public $customer_id;

    public $customer;

    public $sales;

    public $warehouse_id;

    public function mount($id): void
    {
        $this->customer = Customer::where('id', $id)->firstOrFail();
        $this->customer_id = $this->customer->id;
    }

    #[Computed]
    public function sales()
    {
        $query = Sale::where('customer_id', $this->customer_id)
            ->with('customer')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function customerPayments()
    {
        $query = Sale::where('customer_id', $this->customer_id)
            ->with('salepayments.sale')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
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
        return SaleReturn::whereBelongsTo($this->customer)
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
        $salesTotal = Sale::where('customer_id', $this->customer_id)
            ->completed()
            ->sum('total_amount') / 100;

        // Step 2: Calculate total sales returns
        $saleReturnsTotal = SaleReturn::where('customer_id', $this->customer_id)
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

    public function render()
    {
        return view('livewire.customers.details');
    }

    private function customerSum(string $field): int|float
    {
        return Sale::whereBelongsTo($this->customer)->sum($field) / 100;
    }
}
