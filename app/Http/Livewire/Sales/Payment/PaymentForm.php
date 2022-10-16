<?php

namespace App\Http\Livewire\Sales\Payment;

use App\Models\SalePayment;
use Livewire\Component;

class PaymentForm extends Component
{

    public $listeners = [
       'paymentModal','refreshIndex','save'
    ];

    public $paymentModal;

    public $refreshIndex;

    public $salepayment;

    public function mount(SalePayment $salepayment)
    {
        $this->salepayment = $salepayment;
        $this->paymentModal = false;
    }

    public function save()
    {
        $this->validate();
    
        $this->salepayment->save();
    
        $this->emit('refreshIndex');
    
        $this->paymentModal = false;
    }

    public function render()
    {
        return view('livewire.sales.payment.payment-form');
    }
}
