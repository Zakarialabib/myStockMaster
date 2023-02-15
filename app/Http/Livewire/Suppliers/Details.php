<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\WithSorting;
use App\Traits\Datatable;

class Details extends Component
{
    use WithPagination;
    use WithSorting;
    use Datatable;

    public $supplier_id;

    /** @var mixed */
    public $supplier;

    /** @var string[][] */
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

    public function mount($supplier): void
    {
        $this->supplier = Supplier::find($supplier->id);
        $this->supplier_id = $this->supplier->id;
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 20;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Supplier())->orderable;
    }

    public function getTotalPurchasesProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
            ->sum('total_amount');
    }

    public function getTotalPurchaseReturnsProperty()
    {
        return PurchaseReturn::where('supplier_id', $this->supplier_id)
            ->sum('total_amount');
    }

    // total due amount
    public function getTotalDueProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
            ->sum('due_amount') / 100;
    }

    // show totalPayments
    public function getTotalPaymentsProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
            ->sum('paid_amount');
    }

    // show Debit
    public function getDebitProperty()
    {
        $purchases = Purchase::where('supplier_id', $this->supplier_id)
            ->completed()->sum('total_amount');
        $purchase_returns = PurchaseReturn::where('supplier_id', $this->supplier_id)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Purchase::completed()->with('purchaseDetails')->get() as $purchase) {
            foreach ($purchase->purchaseDetails as $purchaseDetail) {
                $product_costs += $purchaseDetail->product->cost;
            }
        }

        $debt = ($purchases - $purchase_returns) / 100;
        $debit = $debt - $product_costs;

        return $debit;
    }

    public function getPurchasesProperty(): mixed
    {
        $query = Purchase::where('supplier_id', $this->supplier_id)
            ->with('supplier')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    public function getSupplierPaymentsProperty(): mixed
    {
        $query = Purchase::where('supplier_id', $this->supplier_id)
            ->with('purchasepayments.purchase')
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.suppliers.details');
    }
}
