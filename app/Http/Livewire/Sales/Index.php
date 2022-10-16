<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Imports\SaleImport;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $sale;

    public $listeners = [
    'confirmDelete', 'delete', 'showModal', 'editModal', 'createModal',
    'importModal', 'import' , 'refreshIndex'
    ];

    public $refreshIndex;

    public $showModal;

    public $createModal;

    public $editModal;

    public $showPayments;

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

    public array $rules = [
        'customer_id' => 'required|numeric',
        'reference' => 'required|string|max:255',
        'tax_percentage' => 'required|integer|min:0|max:100',
        'discount_percentage' => 'required|integer|min:0|max:100',
        'shipping_amount' => 'required|numeric',
        'total_amount' => 'required|numeric',
        'paid_amount' => 'required|numeric',
        'status' => 'required|string|max:255',
        'payment_method' => 'required|string|max:255',
        'note' => 'nullable|string|max:1000'
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Sale())->orderable;
        $this->initListsForFields();
    }

    public function render()
    {
        abort_if(Gate::denies('access_sales'), 403);

        $query = Sale::advancedFilter([
                            's'               => $this->search ?: null,
                            'order_column'    => $this->sortBy,
                            'order_direction' => $this->sortDirection,
                        ]);

        $sales = $query->paginate($this->perPage);

        return view('livewire.sales.index', compact('sales'));
    }

    public function showModal(Sale $sale)
    {
        abort_if(Gate::denies('access_sales'), 403);

        $this->sale = $sale;

        $this->showModal = true;
    }

    public function createModal()
    {
        abort_if(Gate::denies('create_sales'), 403);

        $this->resetSelected();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('create_sales'), 403);

        $this->validate();

        Sale::create($this->sale);

        $this->createModal = false;

        $this->alert('success', 'Sale created successfully.');
    }

    public function editModal(Sale $sale)
    {
        abort_if(Gate::denies('edit_sales'), 403);

        $this->resetSelected();

        $this->resetValidation();

        $this->sale = $sale;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->sale->save();

        $this->editModal   = false;

        $this->alert('success', 'Sale updated successfully.');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('delete_sales'), 403);

        Sale::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Sale $product)
    {
        abort_if(Gate::denies('delete_sales'), 403);

        $product->delete();

        $this->alert('success', 'Sale deleted successfully.');
    }

    public function importModal()
    {
        abort_if(Gate::denies('create_sales'), 403);

        $this->resetSelected();

        $this->resetValidation();

        $this->importModal = true;
    }

    public function import()
    {
        abort_if(Gate::denies('create_sales'), 403);

        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Sale::import(new SaleImport, $this->file('import_file'));

        $this->alert('success', 'Sales imported successfully');

        $this->importModal = false;

    }

    public function showPayments()
    {
        abort_if(Gate::denies('access_sales'), 403);

        $this->showPayments = true;
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['custmers'] = Customer::pluck('name', 'id')->toArray();
    }

  
}
