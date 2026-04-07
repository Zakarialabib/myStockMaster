<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Exports\CustomerExport;
use App\Imports\CustomerImport;
use App\Livewire\Utils\Datatable;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Traits\WithAlert;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]

class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public mixed $customer;

    public mixed $customer_group_id;

    public mixed $file = null;

    public bool $importModal = false;

    public string $model = Customer::class;

    #[Computed]
    public function customerGroups()
    {
        return CustomerGroup::query()->pluck('name', 'id')->toArray();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('customer_access'), 403);

        $query = Customer::query()->when($this->customer_group_id, function ($query): void {
            $query->where('customer_group_id', $this->customer_group_id);
        })->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $customers = $query->paginate($this->perPage);

        return view('livewire.customers.index', ['customers' => $customers]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('customer_delete'), 403);

        Customer::query()->whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Customer $customer): void
    {
        abort_if(Gate::denies('customer_delete'), 403);

        $customer->delete();

        $this->alert('warning', __('Customer deleted successfully'));
    }

    public function downloadSelected(): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        $customers = Customer::query()->whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(Customer $customer): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        return (new CustomerExport($customer))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        return $this->callExport()->forModels($this->selected)->download('customers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        return $this->callExport()->download('customers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function importExcel(): void
    {
        abort_if(Gate::denies('customer_import'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        Excel::import(new CustomerImport, $this->file);

        $this->importModal = false;

        $this->alert('success', __('Customer imported successfully.'));
    }

    private function callExport(): CustomerExport
    {
        return new CustomerExport;
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('customers_import_sample.xls');
    }
}
