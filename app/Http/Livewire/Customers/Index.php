<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Exports\CustomerExport;
use App\Http\Livewire\WithSorting;
use App\Imports\CustomerImport;
use App\Models\Customer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use WithFileUploads;

    /** @var mixed $customer */
    public $customer;

    public $file;

    public int $perPage;

    public $selectPage;

    /** @var string[] $listeners */
    public $listeners = [
        'refreshIndex' => '$refresh',
         'showModal', 'editModal'
    ];

    public $showModal = false;

    public $editModal = false;

    public $refreshIndex;

    public $import;
    /** @var array $orderable */
    public array $orderable;

    /** @var string $search */
    public string $search = '';

    /** @var array $selected */
    public array $selected = [];

    /** @var array $paginationOptions */
    public array $paginationOptions;

    /**
     * @var string[][] $queryString
     */
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

    public array $rules = [
        'customer.name'       => 'required|string|max:255',
        'customer.email'      => 'nullable|max:255',
        'customer.phone'      => 'required|numeric',
        'customer.city'       => 'nullable',
        'customer.country'    => 'nullable',
        'customer.address'    => 'nullable',
        'customer.tax_number' => 'nullable',
    ];

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Customer())->orderable;
    }

    public function render(): View|Factory
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

        $this->alert('warning', __('Customer deleted successfully'));
    }

    public function showModal(Customer $customer)
    {
        abort_if(Gate::denies('customer_show'), 403);

        $this->customer = Customer::find($customer->id);

        $this->showModal = true;
    }

    public function editModal(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->customer = Customer::find($customer->id);

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->customer->save();

        $this->editModal = false;

        $this->alert('success', __('Customer updated successfully.'));
    }

    public function downloadSelected()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(Customer $customers)
    {
        abort_if(Gate::denies('customer_access'), 403);

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function exportAll(Customer $customers)
    {
        abort_if(Gate::denies('customer_access'), 403);

        return (new CustomerExport($customers))->download('customers.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function import()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->import = true;
    }

    public function importExcel()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        Excel::import(new CustomerImport(), $this->file('file'));

        $this->import = false;

        $this->alert('success', __('Customer imported successfully.'));
    }
}
