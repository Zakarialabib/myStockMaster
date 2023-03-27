<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sales;

use App\Enums\PaymentStatus;
use App\Http\Livewire\WithSorting;
use App\Imports\SaleImport;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\Filters\DateFilter;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithFileUploads;
    use LivewireAlert;
    use Datatable;

    /** @var Sale|null */
    public $sale;

    /** @var array<string> */
    public $listeners = [
        'importModal', 'refreshIndex' => '$refresh',
        'paymentModal', 'paymentSave', 'showModal',
        'delete',
    ];


    public $showModal = false;

    public $filterType = null;
    public $startDate;
    public $endDate;

    public $importModal = false;

    public $paymentModal = false;

    public $sale_id;
    public $date;
    public $reference;
    public $amount;
    public $payment_method;

    public $listsForFields = [];

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

    /** @var array */
    protected $rules = [
        'customer_id' => 'required|numeric',
        'reference' => 'required|string|max:255',
        'tax_percentage' => 'required|string|min:0|max:100',
        'discount_percentage' => 'required|string|min:0|max:100',
        'shipping_amount' => 'required|numeric',
        'total_amount' => 'required|numeric',
        'paid_amount' => 'required|numeric',
        'status' => 'required|integer|min:0|max:100',
        'payment_method' => 'required|integer|min:0|max:100',
        'note' => 'string|nullable|max:1000',
    ];

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Sale())->orderable;
        $this->startDate = Sale::orderBy('created_at')->value('created_at');
        $this->endDate = now()->format('Y-m-d');
        $this->initListsForFields();
    }

    public function updatedStartDate($value)
    {
        $this->startDate = $value;
    }

    public function updatedEndDate($value)
    {
        $this->endDate = $value;
    }

    public function filterByType($type)
    {
        $this->filterType = $type;
    }



    protected function filterSalesByDateRange($query)
    {
        switch ($this->filterType) {
            case 'day':
                $this->startDate = now()->format('Y-m-d');
                $this->endDate = now()->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');

                break;
            default:
                $filter = '';
                break;
        }

        $filter = new DateFilter();

        return $filter->filterDate($query, $this->startDate, $this->endDate);
    }

    public function render()
    {
        abort_if(Gate::denies('sale_access'), 403);

        $query = Sale::with(['customer', 'salepayments', 'saleDetails'])
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $sales = $this->filterSalesByDateRange($query)->paginate($this->perPage);

        return view('livewire.sales.index', compact('sales'));
    }


    public function showModal($id)
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale = Sale::find($id);

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('delete_sales'), 403);

        Sale::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Sale $sale)
    {
        abort_if(Gate::denies('delete_sales'), 403);

        $sale->delete();

        $this->emit('refreshIndex');

        $this->alert('success', __('Sale deleted successfully.'));
    }

    public function importModal()
    {
        abort_if(Gate::denies('sale_create'), 403);

        $this->resetSelected();

        $this->resetValidation();

        $this->importModal = true;
    }

    public function import()
    {
        abort_if(Gate::denies('sale_create'), 403);

        $this->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        Sale::import(new SaleImport(), $this->file('import_file'));

        $this->alert('success', __('Sales imported successfully'));

        $this->emit('refreshIndex');

        $this->importModal = false;
    }

    //  Payment modal

    public function paymentModal($id)
    {
        abort_if(Gate::denies('sale_access'), 403);


        $this->sale = Sale::find($id);
        $this->date = date('Y-m-d');
        $this->reference = 'ref-' . date('Y-m-d-h');
        $this->amount = $this->sale->due_amount;
        $this->payment_method = 'Cash';
        $this->sale_id = $this->sale->id;
        $this->paymentModal = true;
    }

    public function paymentSave()
    {
        try {
            $this->validate(
                [
                    'date' => 'required|date',
                    'reference' => 'required|string|max:255',
                    'amount' => 'required|numeric',
                    'payment_method' => 'required|string|max:255',
                ]
            );

            $sale = Sale::find($this->sale_id);

            SalePayment::create([
                'date' => $this->date,
                'reference' => settings()->salepayment_prefix . '-' . date('Y-m-d-h'),
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'sale_id' => $this->sale_id,
                'payment_method' => $this->payment_method,
                'user_id' => Auth::user()->id,
            ]);

            $sale = Sale::findOrFail($this->sale_id);

            $due_amount = $sale->due_amount - $this->amount;

            if ($due_amount === $sale->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $this->alert('success', __('Sale Payment created successfully.'));

            $this->paymentModal = false;

            $this->emit('refreshIndex');
        } catch (Throwable $th) {
            $this->alert('error', __('Error.') . $th->getMessage());
        }
    }

    public function refreshCustomers()
    {
        $this->initListsForFields();
    }

    public function sendWhatsapp($sale)
    {
        $this->sale = Sale::find($sale);

        // Get the customer's phone number and due amount from the model.
        $phone = $this->sale->customer->phone;
        $name = $this->sale->customer->name;

        $dueAmount = format_currency($this->sale->due_amount);

        // Delete the leading zero from the phone number, if it exists.
        if (strpos($phone, '0') === 0) {
            $phone = substr($phone, 1);
        }

        // Add the country code to the beginning of the phone number.
        $phone = '+212' . $phone;

        $greeting = __('Hello');

        $message = __('You have a due amount of');

        // Construct the message text.
        $message = "{$greeting} {$name} {$message} {$dueAmount}.";

        // Encode the message text for use in the URL.
        $message = urlencode($message);

        // Construct the WhatsApp API endpoint URL.
        $url = "https://api.whatsapp.com/send?phone={$phone}&text={$message}";

        return redirect()->away($url);
    }

    public function openWhatapp($url)
    {
        // open whatsapp url in another tab
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['customers'] = Customer::pluck('name', 'id')->toArray();
    }
}
