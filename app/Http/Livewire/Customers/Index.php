<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Exports\CustomerExport;
use App\Http\Livewire\WithSorting;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use LivewireAlert;
    use WithFileUploads;
    use Datatable;

    public $customer;

    public $file;

    public $listeners = [
        'refreshIndex' => '$refresh',
        'showModal',
        'exportAll', 'downloadAll',
        'delete',
    ];

    public $showModal = false;

    public $import;

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

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Customer())->orderable;
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('customer_access'), 403);

        $query = Customer::with('sales')->advancedFilter([
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

    public function delete( $customerID)
    {
        abort_if(Gate::denies('customer_delete'), 403);

        $customer = Customer::findOrFail($customerID);

        $customer->wallet()->delete();


        $customer->delete();

        $this->alert('warning', __('Customer deleted successfully'));
    }

    public function showModal($id): void
    {
        abort_if(Gate::denies('customer_access'), 403);

        $this->customer = Customer::find($id);

        $this->showModal = true;
    }

    public function downloadSelected(): BinaryFileResponse|Response
    {
        abort_if(Gate::denies('customer_access'), 403);

        $customers = Customer::whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(Customer $customers): BinaryFileResponse|Response
    {
        abort_if(Gate::denies('customer_access'), 403);

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): BinaryFileResponse|Response
    {
        abort_if(Gate::denies('customer_access'), 403);

        return $this->callExport()->forModels($this->selected)->download('customers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): BinaryFileResponse|Response
    {
        abort_if(Gate::denies('customer_access'), 403);

        return $this->callExport()->download('customers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function import(): void
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

        $file = $this->file('file');

        Excel::import(new CustomerImport(), $file);

        $this->import = false;

        $this->alert('success', __('Customer imported successfully.'));
    }

    private function callExport(): CustomerExport
    {
        return new CustomerExport();
    }
}
