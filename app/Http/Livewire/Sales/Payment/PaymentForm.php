<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sales\Payment;

use App\Models\Sale;
use App\Models\SalePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Enums\PaymentStatus;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PaymentForm extends Component
{
    use LivewireAlert;
    /** @var string[] */
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
        'date'      => 'required|date',
        'reference' => 'required|string|max:255',
        'amount'    => 'required|numeric',
        'note'      => 'nullable|string|max:1000',
        // 'sale_id' => 'nullable|integer',
        'payment_method' => 'required|string|max:255',
    ];

    // todo : show amount due in amount field
    // make new refrence number
    // make today date

    public function mount(Sale $sale)
    {
        $this->sale = $sale;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->reference = 'ref-'.Carbon::now()->format('YmdHis');
        $this->amount = $sale->due_amount;
    }

    // show sale Payments modal

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
                'date'           => $this->date,
                'reference'      => $this->reference,
                'amount'         => $this->amount,
                'note'           => $this->note ?? null,
                'sale_id'        => $this->sale_id,
                'payment_method' => $this->payment_method,
            ]);

            $sale = Sale::findOrFail($this->sale_id);

            $due_amount = $sale->due_amount - $this->amount;

            if ($due_amount == $sale->total_amount) {
                $payment_status = PaymentStatus::Due;
            } elseif ($due_amount > 0) {
                $payment_status = PaymentStatus::Partial;
            } else {
                $payment_status = PaymentStatus::Paid;
            }

            $sale->update([
                'paid_amount'    => ($sale->paid_amount + $this->amount) * 100,
                'due_amount'     => $due_amount * 100,
                'payment_status' => $payment_status,
            ]);

            $this->alert('success', __('Sale Payment created successfully.'));

            $this->emit('refreshIndex');

            $this->paymentModal = false;

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function render()
    {
        return view('livewire.sales.payment.payment-form');
    }
}
