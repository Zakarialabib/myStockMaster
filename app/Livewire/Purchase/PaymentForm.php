<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class PaymentForm extends Component
{
    use WithAlert;

    public bool $paymentModal = false;

    public Purchase $purchase;

    public mixed $purchase_id;

    #[Validate('required|date')]
    public string $date;

    #[Validate('required|numeric')]
    public mixed $amount;

    #[Validate('required|string|max:255')]
    public string $payment_method;

    public mixed $due_amount;

    public mixed $total_amount;

    public mixed $paid_amount;

    #[Validate('nullable|string|max:1000')]
    public mixed $note;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.purchase.payment-form');
    }

    //  Payment modal

    #[On('paymentModal')]
    public function openPaymentModal(mixed $id): void
    {
        // abort_if(Gate::denies('purchase payment'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->purchase = Purchase::query()->findOrFail($id);
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

            PurchasePayment::query()->create([
                'date' => $this->date,
                'user_id' => Auth::user()->id,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'purchase_id' => $this->purchase_id,
                'payment_method' => $this->payment_method,
            ]);

            $purchase = Purchase::query()->findOrFail($this->purchase_id);

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
                'paid_amount' => ($purchase->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status,
                'status' => $status,
            ]);

            $this->alert('success', __('Purchase Payment created successfully.'));

            $this->paymentModal = false;

            $this->dispatch('refreshIndex')->to(Index::class);
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error' . $throwable->getMessage());
        }
    }
}
