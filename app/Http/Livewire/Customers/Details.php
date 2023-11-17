<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Http\Livewire\WithSorting;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Traits\Datatable;
use Livewire\Component;
use Livewire\WithPagination;

class Details extends Component
{
    use WithPagination;
    use WithSorting;
    use Datatable;

    public $customer_id;

    public $customer;

    /** @var array<array<string>> */
    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public function mount($customer): void
    {
        // dd($customer);
        $this->customer = $customer;
        $this->customer_id = $this->customer->id;
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 20;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Customer())->orderable;
    }

    public function getSalesProperty(): mixed
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

    public function getCustomerPaymentsProperty(): mixed
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

    public function getTotalSalesProperty(): int|float
    {
        return $this->customerSum('total_amount');
    }

    public function getTotalSaleReturnsProperty(): int|float
    {
        return SaleReturn::whereBelongsTo($this->customer)
            ->completed()->sum('total_amount');
    }

    public function getTotalPaymentsProperty(): int|float
    {
        return $this->customerSum('paid_amount');
    }

    // total due amount
    public function getTotalDueProperty(): int|float
    {
        return $this->customerSum('due_amount');
    }

    // show profit
    public function getProfitProperty(): int|float
    {
        // Step 1: Calculate total sales revenue for completed sales
        $salesTotal = Sale::where('customer_id', $this->customer_id)
            ->completed()
            ->sum('total_amount');

        // Step 2: Calculate total sales returns
        $saleReturnsTotal = SaleReturn::where('customer_id', $this->customer_id)
            ->completed()
            ->sum('total_amount');

        // Step 3: Calculate the total product cost from the pivot table
        $productCosts = 0;

        foreach ($this->sales as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                // Assuming you have a warehouses relationship defined on the Product model
                $productWarehouse = $saleDetail->product->product_warehouse->where('warehouse_id', $this->warehouse_id)->first();

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
