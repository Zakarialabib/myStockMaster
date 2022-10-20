<?php

namespace App\Http\Livewire\Sales\Payment;

use App\Models\SalePayment;
use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PaymentForm extends Component
{

    public $listeners = [
       'paymentModal','refreshIndex','save'
    ];

    public SalePayment $salePayment;

    public $paymentModal;

    public $refreshIndex;

    public $sale_id;

    public $reference;

    public $date;

    public $amount;

    public $payment_method;

    public $note;

    public function refreshIndex()
    {
        $this->resetPage();
    }

    protected $rules = [
        'date' => 'required|date',
        'reference' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'note' => 'nullable|string|max:1000',
        // 'sale_id' => 'nullable|integer',
        'payment_method' => 'required|string|max:255'
    ];


    public function mount($salepayment)
    {
        $this->salepayment = $salepayment ?? new SalePayment();

        $this->date = Carbon::now()->format('Y-m-d');

        $this->amount = $this->salepayment->sale->due_amount;
        
    }

    // show sale Payments modal

    public function paymentModal($salepayment)
    {
        abort_if(Gate::denies('access_sales'), 403);
        
        $this->salepayment = $salepayment;

        $this->paymentModal = true;
    }

    public function save()
    {

        DB::transaction(function () {
            
            $this->validate();
            
            $this->sale_id = $this->salepayment->sale->id;

            SalePayment::create([
                'date' => $this->date,
                'reference' => $this->reference,
                'amount' => $this->amount,
                'note' => $this->note ?? null,
                'sale_id' => $this->sale_id,
                'payment_method' => $this->payment_method,
            ]);

            $sale = Sale::findOrFail($this->sale_id);
        
            $due_amount = $sale->due_amount - $this->amount;

            if ($due_amount == $sale->total_amount) {
                $payment_status = Sale::PaymentDue;
            } elseif ($due_amount > 0) {
                $payment_status = Sale::PaymentPartial;
            } else {
                $payment_status = Sale::PaymentPaid;
            }

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $this->amount) * 100,
                'due_amount' => $due_amount * 100,
                'payment_status' => $payment_status
            ]);
        
            $this->emit('refreshIndex');
        
            $this->paymentModal = false;

        }); 
        
    }

    public function render()
    {
        return view('livewire.sales.payment.payment-form');
    }

    
}
