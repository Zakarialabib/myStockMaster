<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Enums\PaymentStatus;
use App\Livewire\Utils\Datatable;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

#[Lazy]
class Index extends Component
{
    use Datatable;
    use WithAlert;
    use WithFileUploads;

    public $purchasereturn;

    public $model = PurchaseReturn::class;

    public $purchase_id;

    public $reference;

    public $due_amount;

    public $total_amount;

    public $paid_amount;

    #[Validate('required|string|max:255')]
    public $payment_method;

    public $paymentModal = false;

    #[Validate('required|date')]
    public $date;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('nullable|string|max:1000')]
    public $note = null;

    public function render()
    {
        $query = PurchaseReturn::with(['supplier', 'purchaseReturnPayments', 'purchaseReturnDetails'])
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
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
            $this->validate();

            PurchasePayment::create([
                'date' => $this->date,
                'user_id' => Auth::user()->id,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'purchase_id' => $this->purchase_id,
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
                'paid_amount' => ($purchasereturn->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $this->alert('success', 'Payment created successfully.');

            $this->paymentModal = false;

            $this->dispatch('refreshIndex');
        } catch (Throwable $throwable) {
            $this->alert('error', __('Error.') . ' ' . $throwable->getMessage());
        }
    }
}
