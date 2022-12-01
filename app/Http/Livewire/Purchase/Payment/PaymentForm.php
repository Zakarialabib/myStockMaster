<?php

namespace App\Http\Livewire\Purchase\Payment;

use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PaymentForm extends Component
{
    public function render(): View|Factory
    {
        return view('livewire.puchase.payment.payment-form');
    }
}
