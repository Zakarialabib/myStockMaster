<?php

namespace App\Http\Livewire\Customers;

use App\Http\Livewire\WithSorting;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleReturn;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Details extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public int $perPage;

    public int $selectPage;

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

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public function mount($customer)
    {
        $this->customer = Customer::findOrFail($customer->id);
        $this->customer_id = $this->customer->id;
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 20;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Customer)->orderable;
    }

    public function getSalesProperty()
    {
        $query = Sale::where('customer_id', $this->customer_id)
        ->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        return $query->paginate($this->perPage);
    }

    public function getCustomerPaymentsProperty()
    {
        $query = Sale::where('customer_id', $this->customer_id)
        ->with('salepayments')
        ->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        return $query->paginate($this->perPage);
    }

    public function getTotalSalesProperty()
    {
        return Sale::where('customer_id', $this->customer_id)
                    ->sum('total_amount') / 100;
    }

    public function getTotalSaleReturnsProperty()
    {
        return SaleReturn::where('customer_id', $this->customer_id)
                                ->sum('total_amount') / 100;
    }

    public function getTotalPaymentsProperty()
    {
        return Sale::where('customer_id', $this->customer_id)
        ->sum('paid_amount') / 100;
    }

    // total due amount
    public function getTotalDueProperty()
    {
        return Sale::where('customer_id', $this->customer_id)
        ->sum('due_amount') / 100;
    }

    // show profit
    public function getProfitProperty()
    {
        $sales = Sale::where('customer_id', $this->customer_id)
                    ->completed()->sum('total_amount');
        $sale_returns = SaleReturn::where('customer_id', $this->customer_id)
                    ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Sale::where('customer_id', $this->customer_id)->with('saleDetails')->get() as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                $product_costs += $saleDetail->product->cost;
            }
        }

        $revenue = ($sales - $sale_returns) / 100;
        $profit = $revenue - $product_costs;

        return $profit;
    }

    public function render()
    {
        return view('livewire.customers.details');
    }
}
