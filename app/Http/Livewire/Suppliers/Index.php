<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Exports\SupplierExport;
use App\Http\Livewire\WithSorting;
use App\Imports\SupplierImport;
use App\Models\Supplier;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;

    /** @var mixed */
    public $supplier;

    /** @var string[] */
    public $listeners = [
        'importModal', 'showModal', 'editModal',
        'refreshIndex' => '$refresh',
        'downloadAll', 'exportAll', 'delete',
    ];

    /** @var bool */
    public $showModal = false;

    /** @var bool */
    public $importModal = false;

    /** @var bool */
    public $editModal = false;

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

    public array $rules = [
        'supplier.name'       => ['required', 'string', 'max:255'],
        'supplier.email'      => ['nullable', 'string', 'max:255'],
        'supplier.phone'      => ['required'],
        'supplier.address'    => ['nullable', 'string', 'max:255'],
        'supplier.city'       => ['nullable', 'string', 'max:255'],
        'supplier.country'    => ['nullable', 'string', 'max:255'],
        'supplier.tax_number' => ['nullable', 'string', 'max:255'],
    ];

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Supplier())->orderable;
    }

    public function render(): View|Factory
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

    public function showModal($id)
    {
        $this->supplier = Supplier::find($id);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function editModal($id)
    {
        abort_if(Gate::denies('supplier_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->supplier = Supplier::find($id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->supplier->save();

        $this->alert('success', __('Supplier Updated Successfully'));

        $this->editModal = false;
    }

    public function delete(Supplier $supplier)
    {
        abort_if(Gate::denies('supplier_delete'), 403);

        $supplier->delete();

        $this->alert('warning', __('Supplier Deleted Successfully'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('supplier_delete'), 403);

        Supplier::whereIn('id', $this->selected)->delete();

        $this->selected = [];
    }

    public function importModal()
    {
        abort_if(Gate::denies('supplier_import'), 403);

        $this->importModal = true;
    }

    public function import()
    {
        abort_if(Gate::denies('supplier_import'), 403);

        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Supplier::import(new SupplierImport(), $this->file('import_file'));

        $this->alert('success', __('Supplier Imported Successfully'));

        $this->importModal = false;
    }

    public function downloadSelected()
    {
        abort_if(Gate::denies('supplier_access'), 403);

        $suppliers = Supplier::whereIn('id', $this->selected)->get();

        return (new SupplierExport($suppliers))->download('suppliers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(Supplier $suppliers)
    {
        abort_if(Gate::denies('supplier_access'), 403);

        return (new SupplierExport($suppliers))->download('suppliers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): BinaryFileResponse
    {
        abort_if(Gate::denies('supplier_access'), 403);

        $suppliers = Supplier::whereIn('id', $this->selected)->get();

        return $this->callExport()->forModels($this->selected)->download('suppliers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): BinaryFileResponse
    {
        abort_if(Gate::denies('supplier_access'), 403);

        return $this->callExport()->download('suppliers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): SupplierExport
    {
        return (new SupplierExport());
    }
}
