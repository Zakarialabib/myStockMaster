<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Http\Livewire\WithSorting;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Details extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public int $perPage;

    public $selectPage;

    public $customer_id;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

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

    public function refreshIndex(): void
    {
        $this->resetPage();
    }

    public function mount($customer): void
    {
        $this->customer = Customer::findOrFail($customer->id);
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
        $query = $this->customer()
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    public function getCustomerPaymentsProperty(): mixed
    {
        $query = $this->customer()
            ->with('salepayments')
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
        return $this->customerSum('total_amount');
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
        $sales = $this->customer()
            ->completed()->sum('total_amount');
        $sale_returns = SaleReturn::whereBelongsTo($this->customer)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach ($this->customer()->with('saleDetails')->get() as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                $product_costs += $saleDetail->product->cost;
            }
        }

        $revenue = ($sales - $sale_returns) / 100;
        $profit = $revenue - $product_costs;

        return $profit;
    }

    private function customerSum(string $field): int|float
    {
        return Sale::whereBelongsTo($this->customer)->sum($field) / 100;
    }

    private function customer(): Builder
    {
        return Sale::whereCustomerId($this->customer_id);
    }

    public function render(): View|Factory
    {
        return view('livewire.customers.details');
    }
}
