<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Exports\SaleExport;
use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\WithModels;
use App\Models\Sale;
use App\Traits\WithAlert;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    /** @var Sale|null */
    public $sale;

    public $model = Sale::class;

    public $startDate;

    public $endDate;

    public $importModal = false;

    // public $deleteModal = false;

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
    }

    public function filterByType($type): void
    {
        switch ($type) {
            case 'day':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');

                break;
        }
    }

    public function render()
    {
        abort_if(Gate::denies('sale_access'), 403);

        $query = Sale::with(['customer', 'user', 'salePayments'])
            ->withSum('saleDetails', 'quantity')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $sales = $query->paginate($this->perPage);

        return view('livewire.sales.index', ['sales' => $sales]);
    }

    #[On('importModal')]
    public function openImportModal(): void
    {
        abort_if(Gate::denies('sale_create'), 403);

        $this->importModal = true;
    }

    public function downloadSelected()
    {
        abort_if(Gate::denies('sale_access'), 403);

        $sales = Sale::whereIn('id', $this->selected)->get();

        return (new SaleExport($sales))->download('sales.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    #[On('downloadAll')]
    public function downloadAll(): StreamedResponse|Response
    {
        abort_if(Gate::denies('sale_access'), 403);

        return $this->callExport()->download('sales.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): StreamedResponse|Response
    {
        abort_if(Gate::denies('sale_access'), 403);

        return $this->callExport()->forModels($this->selected)->download('sales.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    #[On('exportAll')]
    public function exportAll(): StreamedResponse|Response
    {
        abort_if(Gate::denies('sale_access'), 403);

        return $this->callExport()->download('sales.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): SaleExport
    {
        return new SaleExport;
    }

    public function downloadSample(): StreamedResponse|Response
    {
        return Storage::disk('exports')->download('sales_import_sample.xls');
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('sale_delete'), 403);

        Sale::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('sale_delete'), 403);

        Sale::findOrFail($this->sale)->delete();

        $this->dispatch('refreshIndex');

        $this->alert('success', __('Sale deleted successfully.'));
    }

    public function deleteModal($id): void
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'delete',
        ]);
        $this->sale = $id;
    }

    public function sendWhatsapp($sale)
    {
        $this->sale = Sale::find($sale);

        // Get the customer's phone number and due amount from the model.
        $phone = $this->sale->customer->phone;
        $name = $this->sale->customer->name;

        $dueAmount = format_currency($this->sale->due_amount);

        // Delete the leading zero from the phone number, if it exists.
        if (str_starts_with((string) $phone, '0')) {
            $phone = substr((string) $phone, 1);
        }

        // Add the country code to the beginning of the phone number.
        $phone = '+212' . $phone;

        $greeting = __('Hello');

        $message = __('You have a due amount of');

        // Construct the message text.
        $message = sprintf('%s %s %s %s.', $greeting, $name, $message, $dueAmount);

        // Encode the message text for use in the URL.
        $message = urlencode($message);

        // Construct the WhatsApp API endpoint URL.
        $url = sprintf('https://api.whatsapp.com/send?phone=%s&text=%s', $phone, $message);

        return redirect()->away($url);
    }

    public function openWhatapp($url): void
    {
        // open whatsapp url in another tab
    }
}
