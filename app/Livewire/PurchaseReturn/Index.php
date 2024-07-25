<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Enums\PaymentStatus;
use App\Livewire\Utils\Datatable;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class Index extends Component
{
    use Datatable;
    use WithFileUploads;
    use LivewireAlert;

    public $purchasereturn;

    public $model = PurchaseReturn::class;

    /** @var array<string> */
    public $listeners = [
        'delete', 'paymentModal', 'paymentSave',
    ];

    public $purchase_id;

    public $date;

    public $reference;

    public $amount;

    public $due_amount;

    public $total_amount;

    public $paid_amount;

    public $payment_method;

    public $paymentModal = false;

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

    public function render()
    {
        $query = PurchaseReturn::with(['supplier', 'purchaseReturnPayments', 'purchaseReturnDetails'])
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $purchasereturns = $query->paginate($this->perPage);

        return view('livewire.purchase-return.index', ['purchasereturns' => $purchasereturns]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        PurchaseReturn::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(PurchaseReturn $purchasereturn): void
    {
        abort_if(Gate::denies('purchase_delete'), 403);

        $purchasereturn->delete();
    }

    public function paymentModal(PurchaseReturn $purchasereturn): void
    {
        abort_if(Gate::denies('purchase payment'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchasereturn = $purchasereturn;
        $this->date = date('Y-m-d');
        $this->amount = $purchasereturn->due_amount;
        $this->payment_method = 'Cash';
        $this->purchase_id = $purchasereturn->id;
        $this->paymentModal = true;
    }

    public function paymentSave(): void
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

            $this->dispatch('refreshIndex');
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error'.$throwable->getMessage());
        }
    }
}
