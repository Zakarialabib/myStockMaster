<?php

namespace App\Http\Livewire\Suppliers;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Exports\SupplierExport;
use App\Imports\SupplierImport;
use App\Models\Wallet;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $supplier;

    public int $perPage;

    public $listeners = ['confirmDelete', 'delete', 'export', 'import','refreshIndex','showModal','editModal'];

    public $showModal;

    public $editModal;

    public array $orderable;

    public $selectPage;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $refreshIndex;

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

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public array $rules = [
        'supplier.name' => ['required', 'string', 'max:255'],
        'supplier.email' => ['nullable', 'string', 'max:255'],
        'supplier.phone' => ['required'],
        'supplier.address' => ['nullable', 'string', 'max:255'],
        'supplier.city' => ['nullable', 'string', 'max:255'],
        'supplier.country' => ['nullable', 'string', 'max:255'],
        'supplier.tax_number' => ['nullable', 'string', 'max:255'],
    ];

    public function mount()
    {
        $this->selectPage = false;
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');        
        $this->orderable = (new Supplier())->orderable;
    }

    public function render()
    {   
        abort_if(Gate::denies('supplier_access'), 403);

        $query = Supplier::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $suppliers = $query->paginate($this->perPage);

        return view('livewire.suppliers.index', compact('suppliers'));
    }

    public function showModal(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->showModal = true;
    }

    public function editModal(Supplier $supplier)
    {
        abort_if(Gate::denies('supplier_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();
        
        $this->supplier = $supplier;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->supplier->save();

        $this->alert('success', 'Supplier Updated Successfully');

        $this->editModal = false;

    }

    public function delete(Supplier $supplier)
    {
        abort_if(Gate::denies('supplier_delete'), 403);

        $supplier->delete();

        $this->alert('warning', 'Supplier Deleted Successfully');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('supplier_delete'), 403);

        Supplier::whereIn('id', $this->selected)->delete();

        $this->selected = [];
    }

    public function import()
    {
        abort_if(Gate::denies('supplier_import'), 403);

        $this->validate([
            'import_file' => 'required',
        ]);

        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Supplier::import(new SupplierImport, request()->file('import_file'));

        $this->alert('success', 'Supplier Imported Successfully');
    }

    public function export()
    {
        abort_if(Gate::denies('supplier_export'), 403);

        return (new SupplierExport)->download('supplier.xlsx');
    }

    
}
