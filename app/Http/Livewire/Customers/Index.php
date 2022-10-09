<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Exports\CustomerExport;
use App\Support\HasAdvancedFilter;
use App\Imports\CustomerImport;
use App\Models\Wallet;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, LivewireAlert, HasAdvancedFilter;

    public $customer;

    public int $perPage;

    public int $selectPage;

    public $listeners = ['confirmDelete', 'delete', 'export', 'import','refreshIndex','showModal','editModal'];

    public $showModal;

    public $refreshIndex;

    public $editModal; 

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

    public array $rules = [
        'customer.name' => 'required|string|max:255',
        'customer.email' => 'nullable|max:255',
        'customer.phone' => 'required|numeric',
        'customer.city' => 'nullable',
        'customer.country' => 'nullable',
        'customer.address' => 'nullable',
        'customer.tax_number' => 'nullable',
    ];

    public function mount()
    {
        $this->selectPage = false;
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Customer())->orderable;
    }

    public function render()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $query = Customer::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $customers = $query->paginate($this->perPage);

        return view('livewire.customers.index', compact('customers'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('customer_delete'), 403);

        Customer::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), 403);

        $customer->delete();

        $this->alert('warning', 'Customer deleted successfully');
    }
    
    public function createModal()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->customer = new Customer();

        $this->createModal = true;
    }

    public function create()
    {
        $this->validate();

        $this->customer->save();

        $this->createModal = false;

        $this->alert('success', 'Customer created successfully.');
    }

    public function showModal(Customer $customer)
    {
        abort_if(Gate::denies('customer_show'), 403);

        $this->customer = $customer;

        $this->showModal = true;
    }

    public function editModal(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->customer = $customer;

        $this->editModal = true;

    }

    public function update()
    {
        $this->validate();

        $this->customer->save();

        $this->editModal = false;

        $this->alert('success', 'Customer updated successfully.');
    }
    
    public function downloadSelected()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.xlsx');
    }

    public function downloadAll()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::all();

        return (new CustomerExport($customers))->download('customers.xlsx');
    }

    public function exportSelected()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.pdf');
    }

    public function exportAll()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::all();

        return (new CustomerExport($customers))->download('customers.pdf');
    }

    public function import()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Customer::import(new CustomerImport, request()->file('import_file'));

        $this->reset('import_file');
    }

}
