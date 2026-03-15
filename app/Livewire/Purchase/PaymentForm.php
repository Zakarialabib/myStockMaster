<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Throwable;
use App\Traits\WithAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class PaymentForm extends Component
{
    use WithAlert;
    public bool $paymentModal = false;

    public Purchase $purchase;

    public $purchase_id;

    #[Validate('required|date')]
    public string $date;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('required|string|max:255')]
    public string $payment_method;

    public $due_amount;

    public $total_amount;

    public $paid_amount;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public function render()
    {
        return view('livewire.purchase.payment-form');
    }

    //  Payment modal

    #[On('paymentModal')]
    public function openPaymentModal($id): void
    {
        // abort_if(Gate::denies('purchase payment'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = Purchase::findOrFail($id);
        $this->date = date('Y-m-d');
        $this->amount = $this->purchase->due_amount;
        $this->payment_method = 'Cash';
        $this->purchase_id = $this->purchase->id;
        $this->paymentModal = true;
    }

    public function paymentSave(): void
    {
        try {
            $this->validate();

            PurchasePayment::create([
                'date'           => $this->date,
                'user_id'        => Auth::user()->id,
                'amount'         => $this->amount,
                'note'           => $this->note ?? null,
                'purchase_id'    => $this->purchase_id,
                'payment_method' => $this->payment_method,
            ]);

            $purchase = Purchase::findOrFail($this->purchase_id);

            $due_amount = $purchase->due_amount - $this->amount;

            if ($due_amount === $purchase->total_amount) {
                $payment_status = PaymentStatus::DUE;
                $status = PurchaseStatus::PENDING;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
                $status = PurchaseStatus::PENDING;
            } else {
                $payment_status = PaymentStatus::PAID;
                $status = PurchaseStatus::COMPLETED;
            }

            $purchase->update([
                'paid_amount'    => ($purchase->paid_amount + $this->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
                'status'         => $status,
            ]);

            $this->alert('success', __('Purchase Payment created successfully.'));

            $this->paymentModal = false;

            $this->dispatch('refreshIndex')->to(Index::class);
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error'.$throwable->getMessage());
        }
    }
}
