<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Http\Livewire\WithSorting;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Imports\SaleImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $sale;

    public $listeners = [
    'confirmDelete', 'delete', 'showModal',
    'importModal', 'import' , 'refreshIndex',
    'paymentModal', 'paymentSave', 
    ];

    public $refreshIndex;

    public $showModal;
    
    public $importModal;    

    public $paymentModal;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    // public $salepayments;
    
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
        'note' => 'string|max:1000'
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

        $query = Sale::with(['customer', 'salepayments'])
                      ->advancedFilter([
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

        $this->sale = Sale::find($sale->id);

        $this->showModal = true;
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
        
        $this->emit('refreshIndex');
        
        $this->alert('success', __('Sale deleted successfully.'));
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

        $this->alert('success', __('Sales imported successfully'));

        $this->emit('refreshIndex');

        $this->importModal = false;
        

    }

    //  Payment modal

    public function paymentModal(Sale $sale)
    {
        abort_if(Gate::denies('access_sales'), 403);

        $this->sale = $sale;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->reference = 'ref-'.Carbon::now()->format('YmdHis');
        $this->amount = $sale->due_amount;
        $this->payment_method = 'Cash';
        // $this->note = '';
        $this->sale_id = $sale->id;
        $this->paymentModal = true;
    }

    public function paymentSave(){
        DB::transaction(function () {
            
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
                'reference' => $this->reference,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'sale_id' => $this->sale_id,
                'payment_method' => $this->payment_method,
            ]);

            $sale = Sale::findOrFail($this->sale_id);
        
            $due_amount = $sale->due_amount - $this->amount;

            if ($due_amount == $sale->total_amount) {
                $payment_status = Sale::PaymentDue;
            } elseif ($due_amount > 0) {
                $payment_status = Sale::PaymentPartial;
            } else {
                $payment_status = Sale::PaymentPaid;
            }

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status
            ]);
        
            $this->emit('refreshIndex');
        
            $this->paymentModal = false;

        }); 
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['customers'] = Customer::pluck('name', 'id')->toArray();
    }
    
    public function refreshCustomers()
    {
        $this->initListsForFields();
    }

}
