<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Models\Sale;
use App\Models\SalePayment;
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

    public Sale $sale;

    public mixed $sale_id;

    // public mixed $reference;

    #[Validate('required|date')]
    public ?string $date = null;

    #[Validate('required|numeric')]
    public mixed $amount;

    #[Validate('required|string|max:100')]
    public mixed $payment_method;

    #[Validate('nullable|numeric')]
    public mixed $total_amount;

    #[Validate('nullable|numeric')]
    public mixed $due_amount;

    #[Validate('nullable|numeric')]
    public mixed $paid_amount;

    #[Validate('nullable|string|max:1000')]
    public mixed $note;

    //  Payment modal

    #[On('paymentModal')]
    public function paymentModal(mixed $id): void
    {
        // abort_if(Gate::denies('sale_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->sale = Sale::query()->findOrFail($id);
        $this->date = date('Y-m-d');
        $this->amount = $this->sale->due_amount;
        $this->payment_method = 'Cash';
        $this->sale_id = $this->sale->id;
        $this->paymentModal = true;
    }

    public function paymentSave(): void
    {
        try {
            $this->validate();

            SalePayment::query()->create([
                'date' => $this->date,
                'amount' => $this->amount,
                'note' => $this->note,
                'sale_id' => $this->sale_id,
                'payment_method' => $this->payment_method,
                'user_id' => Auth::user()->id,
            ]);

            $sale = Sale::query()->findOrFail($this->sale_id);

            $due_amount = $sale->due_amount - $this->amount;

            if ($due_amount === $sale->total_amount) {
                $payment_status = PaymentStatus::DUE;
                $status = SaleStatus::PENDING;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::PARTIAL;
                $status = SaleStatus::PENDING;
            } else {
                $payment_status = PaymentStatus::PAID;
                $status = SaleStatus::COMPLETED;
            }

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status,
                'status' => $status,
            ]);

            $this->alert('success', __('Sale Payment created successfully.'));

            $this->paymentModal = false;

            $this->dispatch('refreshIndex')->to(Index::class);
        } catch (Throwable $throwable) {
            $this->alert('error', __('Error.') . $throwable->getMessage());
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.sales.payment-form');
    }
}
