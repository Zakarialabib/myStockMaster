<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sales\Payment;

use App\Enums\PaymentStatus;
use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class PaymentForm extends Component
{
    use LivewireAlert;
    /** @var array<string> */
    public $listeners = [
        'paymentModal',
        'refreshIndex' => '$refresh',
    ];

    public $paymentModal;

    public $sale;

    public $salepayment;

    public $sale_id;

    public $reference;

    public $date;

    public $amount;

    public $payment_method;

    public $note;

    protected $rules = [
        'date' => 'required|date',
        'reference' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'note' => 'nullable|string|max:1000',
        // 'sale_id' => 'nullable|integer',
        'payment_method' => 'required|string|max:255',
    ];

    public function mount(Sale $sale)
    {
        $this->sale = $sale;
        $this->date = date('Y-m-d');
        $this->reference = 'ref-'.date('Y-m-d-h');
        $this->amount = $sale->due_amount;
    }

    public function paymentModal($sale)
    {
        abort_if(Gate::denies('sale_access'), 403);

        $this->sale_id = $sale;

        $this->paymentModal = true;
    }

    public function save()
    {
        try {
            $this->validate();

            $this->sale = $this->salepayment->sale->id;

            SalePayment::create([
                'date' => $this->date,
                'reference' => $this->reference,
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
            $this->alert('error', 'Error'.$th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.payment.payment-form');
    }
}
