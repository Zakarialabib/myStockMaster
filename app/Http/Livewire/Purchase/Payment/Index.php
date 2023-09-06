<?php

declare(strict_types=1);

namespace App\Http\Livewire\Purchase\Payment;

use App\Http\Livewire\WithSorting;
use App\Models\PurchasePayment;
use App\Models\Purchase;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use Datatable;

    public $purchase;

    /** @var array<string> */
    public $listeners = [
        'showPayments',
        'refreshIndex' => '$refresh',
    ];

    public $showPayments;

    public $listsForFields = [];

    public $purchase_id;

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

    public function mount(Purchase $purchase): void
    {
        $this->purchase = $purchase;
        $this->perPage = 10;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new PurchasePayment())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('purchase_payment_access'), 403);

        $query = PurchasePayment::where('purchase_id', $this->purchase->id)->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $purchasepayments = $query->paginate($this->perPage);

        return view('livewire.purchase.payment.index', compact('purchasepayments'));
    }

    public function showPayments($purchase_id): void
    {
        abort_if(Gate::denies('purchase_payment_access'), 403);

        $this->purchase = Purchase::findOrFail($purchase_id);

        $this->showPayments = true;
    }
}
