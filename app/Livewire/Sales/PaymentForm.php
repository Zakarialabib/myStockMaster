<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class PaymentForm extends Component
{
    use LivewireAlert;

    public $paymentModal = false;

    public $sale;

    public $sale_id;

    // public $reference;

    #[Validate('required|date')]
    public $date;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('required|string|max:100')]
    public $payment_method;

    #[Validate('nullable|numeric')]
    public $total_amount;

    #[Validate('nullable|numeric')]
    public $due_amount;

    #[Validate('nullable|numeric')]
    public $paid_amount;

    #[Validate('nullable|string|max:1000')]
    public $note;

    protected $rules = [
        'date'   => '',
        'amount' => '',
        'note'   => 'nullable|string|max:1000',
        // 'sale_id' => 'nullable|integer',
        'payment_method' => 'required|string|max:255',
    ];

    //  Payment modal

    #[On('paymentModal')]
    public function paymentModal($id): void
    {
        // abort_if(Gate::denies('sale_access'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->sale = Sale::findOrFail($id);
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

            SalePayment::create([
                'date'           => $this->date,
                'amount'         => $this->amount,
                'note'           => $this->note,
                'sale_id'        => $this->sale_id,
                'payment_method' => $this->payment_method,
                'user_id'        => Auth::user()->id,
            ]);

            $sale = Sale::findOrFail($this->sale_id);

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
                'paid_amount'    => ($sale->paid_amount + $this->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
                'status'         => $status,
            ]);

            $this->alert('success', __('Sale Payment created successfully.'));

            $this->paymentModal = false;

            $this->dispatch('refreshIndex')->to(Index::class);
        } catch (Throwable $throwable) {
            $this->alert('error', __('Error.').$throwable->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.payment-form');
    }
}
