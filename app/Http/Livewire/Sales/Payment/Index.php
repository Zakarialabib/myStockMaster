<?php

namespace App\Http\Livewire\Sales\Payment;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert;
    
    public $sale;

    public $listeners = [
        'delete', 'showPayments', 'paymentModal','refreshIndex'
    ];

    public $refreshIndex;

    public $showPayments;

    public $paymentModal;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;
    
    public array $listsForFields = [];

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

    public function mount()
    {
        $this->perPage = 10;
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new SalePayment())->orderable;
        $this->paymentModal = false;
    }


    public function render(SalePayment $sale_id)
    {
    //    abort_if(Gate::denies('access_sale_payments'), 403);

       $query = SalePayment::where('sale_id', $sale_id)->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $salespayment = $query->paginate($this->perPage);

        return view('livewire.sales.payment.index', compact('salespayment'));
    }
}
