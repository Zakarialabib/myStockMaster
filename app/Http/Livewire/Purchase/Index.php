<?php

namespace App\Http\Livewire\Purchase;
 
use Livewire\Component;
use App\Http\Livewire\WithSorting;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination, WithSorting, WithFileUploads, LivewireAlert;

    public $purchase;

    public int $perPage;

    public $listeners = [
     'confirmDelete', 'delete', 'showModal', 'editModal',
     'createModal','paymentModal' , 'paymentSave', 'refreshIndex'
    ];

    public $showModal;

    public $createModal;

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

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public array $rules = [
        'supplier_id' => 'required|numeric',
        'reference' => 'required|string|max:255',
        'tax_percentage' => 'required|integer|min:0|max:100',
        'discount_percentage' => 'required|integer|min:0|max:100',
        'shipping_amount' => 'required|numeric',
        'total_amount' => 'required|numeric',
        'paid_amount' => 'required|numeric',
        'status' => 'required|string|max:255',
        'payment_method' => 'required|string|max:255',
        'note' => 'nullable|string|max:1000'
    ];

    public function mount()
    {
        $this->selectPage = false;
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new Purchase())->orderable;
    }

    public function render()
    {
        $query = Purchase::with(['supplier', 'purchaseDetails', 'purchaseDetails.product'])
                           ->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $purchases = $query->paginate($this->perPage);

        return view('livewire.purchase.index', compact('purchases'));
    }

    public function createModal()
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset();

        $this->createModal = true;
    }

    public function create()
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->validate();

        Purchase::create($this->purchase);

        $this->createModal = false;

        $this->alert('success', 'Purchase created successfully.');
    }

    public function editModal(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = $purchase;

        $this->editModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->purchase->save();

        $this->editModal = false;

        $this->alert('success', 'Purchase updated successfully.');
    }

    public function showModal(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = $purchase;

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        Purchase::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        $purchase->delete();
    }


    //  Payment modal

    public function paymentModal(Purchase $purchase)
    {
        abort_if(Gate::denies('purchase_payment'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = $purchase;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->reference = 'ref-'.Carbon::now()->format('YmdHis');
        $this->amount = $purchase->due_amount;
        $this->payment_method = 'Cash';
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
            
            $purchase = Purchase::find($this->purchase_id);

            PurchasePayment::create([
                'date' => $this->date,
                'reference' => $this->reference,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'purchase_id' => $this->purchase_id,
                'payment_method' => $this->payment_method,
            ]);

            $purchase = Purchase::findOrFail($this->purchase_id);
        
            $due_amount = $purchase->due_amount - $this->amount;

            if ($due_amount == $purchase->total_amount) {
                $payment_status = Purchase::PaymentDue;
            } elseif ($due_amount > 0) {
                $payment_status = Purchase::PaymentPartial;
            } else {
                $payment_status = Purchase::PaymentPaid;
            }

            $purchase->update([
                'paid_amount' => ($purchase->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status
            ]);
        
            $this->emit('refreshIndex');

            $this->alert('success', 'Payment created successfully.');

            $this->paymentModal = false;

        }); 
    }

}
