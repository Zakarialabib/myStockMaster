<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Livewire\Utils\WithModels;
use App\Models\Sale;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Utils\Datatable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;
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

        $query = Sale::with(['customer', 'user', 'saleDetails', 'salepayments', 'saleDetails.product'])
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $sales = $query->paginate($this->perPage);

        return view('livewire.sales.index', ['sales' => $sales]);
    }

    public function openImportModal(): void
    {
        abort_if(Gate::denies('sale_create'), 403);

        $this->importModal = true;
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
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
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
        $phone = '+212'.$phone;

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
