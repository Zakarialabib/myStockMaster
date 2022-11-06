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

    public $sale_id;

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

    public function mount($sale){
        $this->sale = $sale;
        
        if($sale){
            $this->sale_id = $sale->id;
        }

        $this->perPage = 10;
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new SalePayment())->orderable;
        $this->paymentModal = false;
    }

    public function render()
    {
        //    abort_if(Gate::denies('access_sale_payments'), 403);

       $query = SalePayment::where('sale_id', $this->sale_id)->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $salepayments = $query->paginate($this->perPage);

        return view('livewire.sales.payment.index', compact('salepayments'));
    }

    public function showPayments($sale_id)
    {
        abort_if(Gate::denies('access_sales'), 403);
        
        $this->sale_id = $sale_id;

        $this->showPayments = true;
    }


}
