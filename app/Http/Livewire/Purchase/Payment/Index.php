<?php

namespace App\Http\Livewire\Purchase\Payment;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;

    public $purchase;

    public $listeners = [
        'delete', 'showPayments', 'refreshIndex'
    ];

    public $refreshIndex;

    public $showPayments;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public array $listsForFields = [];

    public $purchase_id;

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

    public function updatingPerPage()
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

    public function mount($purchase)
    {
        $this->purchase = $purchase;

        if ($purchase) {
            $this->purchase_id = $purchase->id;
        }

        $this->perPage = 10;
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new PurchasePayment())->orderable;
        $this->paymentModal = false;
    }

    public function render()
    {
        //    abort_if(Gate::denies('access_purchase_payments'), 403);

        $query = PurchasePayment::where('purchase_id', $this->purchase_id)->advancedFilter([
             's'               => $this->search ?: null,
             'order_column'    => $this->sortBy,
             'order_direction' => $this->sortDirection,
         ]);

        $purchasepayments = $query->paginate($this->perPage);

        return view('livewire.purchase.payment.index', compact('purchasepayments'));
    }

    public function showPayments($purchase_id)
    {
        abort_if(Gate::denies('access_purchases'), 403);

        $this->purchase_id = $purchase_id;

        $this->showPayments = true;
    }
}
