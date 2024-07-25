<?php

declare(strict_types=1);

namespace App\Http\Livewire\PurchaseReturn;

use App\Enums\PaymentStatus;
use App\Http\Livewire\WithSorting;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Traits\Datatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
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

    public $purchasereturn;

    /** @var array<string> */
    public $listeners = [
        'showModal', 'editModal', 'delete',
        'createModal', 'paymentModal', 'paymentSave',
        'refreshIndex' => '$refresh',
    ];

    public $showModal = false;

    public $createModal;

    public $editModal = false;

    public $purchase_id;
    public $date;
    public $reference;
    public $amount;
    public $payment_method;
    public $paymentModal = false;

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
        'supplier_id'         => 'required|numeric',
        'reference'           => 'required|string|max:255',
        'tax_percentage'      => 'required|integer|min:0|max:100',
        'discount_percentage' => 'required|integer|min:0|max:100',
        'shipping_amount'     => 'required|numeric',
        'total_amount'        => 'required|numeric',
        'paid_amount'         => 'required|numeric',
        'status'              => 'required|integer|max:255',
        'payment_method'      => 'required|integer|max:255',
        'note'                => 'nullable|string|max:1000',
    ];

    public function mount(): void
    {
        $this->selectPage = false;
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new PurchaseReturn())->orderable;
    }

    public function render()
    {
        $query = PurchaseReturn::with(['supplier', 'purchaseReturnPayments', 'purchaseReturnDetails'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $purchasereturns = $query->paginate($this->perPage);

        return view('livewire.purchase-return.index', compact('purchasereturns'));
    }

    public function createModal(): void
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset();

        $this->createModal = true;
    }

    public function create(): void
    {
        abort_if(Gate::denies('purchase_create'), 403);

        $this->validate();

        PurchaseReturn::create($this->purchase);

        $this->createModal = false;

        $this->alert('success', 'PurchaseReturn created successfully.');
    }

    public function editModal(PurchaseReturn $purchasereturn)
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = $purchasereturn;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->purchasereturn->save();

        $this->editModal = false;

        $this->alert('success', 'PurchaseReturn updated successfully.');
    }

    public function showModal(PurchaseReturn $purchasereturn)
    {
        abort_if(Gate::denies('purchase_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = $purchasereturn;

        $this->showModal = true;
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        PurchaseReturn::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(PurchaseReturn $purchasereturn)
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        $purchasereturn->delete();
    }

    //  Payment modal

    public function paymentModal(PurchaseReturn $purchasereturn)
    {
        abort_if(Gate::denies('purchase_payment'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = $purchasereturn;
        $this->date = date('Y-m-d');
        $this->amount = $purchasereturn->due_amount;
        $this->payment_method = 'Cash';
        $this->purchase_id = $purchasereturn->id;
        $this->paymentModal = true;
    }

    public function paymentSave()
    {
        try {
            $this->validate(
                [
                    'date'           => 'required|date',
                    'amount'         => 'required|numeric',
                    'payment_method' => 'required|string|max:255',
                ]
            );

            $purchasereturn = PurchaseReturn::find($this->purchase_id);

            PurchasePayment::create([
                'date'           => $this->date,
                'user_id'        => Auth::user()->id,
                'amount'         => $this->amount,
                'note'           => $this->note ?? null,
                'purchase_id'    => $this->purchase_id,
                'payment_method' => $this->payment_method,
            ]);

            $purchasereturn = PurchaseReturn::findOrFail($this->purchase_id);

            $due_amount = $purchasereturn->due_amount - $this->amount;

            if ($due_amount === $purchasereturn->total_amount) {
                $payment_status = PaymentStatus::DUE;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
            } else {
                $payment_status = PaymentStatus::PAID;
            }

            $purchasereturn->update([
                'paid_amount'    => ($purchasereturn->paid_amount + $this->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $this->alert('success', 'Payment created successfully.');

            $this->paymentModal = false;

            $this->emit('refreshIndex');
        } catch (Throwable $th) {
            $this->alert('error', 'Error'.$th->getMessage());
        }
    }
}
