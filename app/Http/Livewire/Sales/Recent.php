<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Imports\SaleImport;

class Recent extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $sale;

    public $listeners = [
    'recentSales', 'showModal',
    'importModal', 'refreshIndex'
    ];

    public $refreshIndex;

    public $showModal;
    
    public $recentSales;    

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
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 10;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Sale())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('access_sales'), 403);

        $query = Sale::with('customer','saleDetails')->advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $sales = $query->paginate($this->perPage);

        return view('livewire.sales.recent', compact('sales'));
    }

    public function showModal(Sale $sale)
    {
        abort_if(Gate::denies('access_sales'), 403);

        $this->sale = $sale;

        $this->showModal = true;
    }

    public function recentSales()
    {
        abort_if(Gate::denies('access_sales'), 403);

        $this->recentSales = true;
    }
  
}
