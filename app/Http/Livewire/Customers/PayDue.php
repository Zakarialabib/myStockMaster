<?php

namespace App\Http\Livewire\Customers;

use App\Models\SalePayment;
use App\Models\Sale;
use Auth;
use Carbon\Carbon;
use Livewire\Component;

class PayDue extends Component
{
    // get customer id
    // pay due amount

    public function pay()
    {
        if($this['amount'] > 0) {
            $customer_sales_due = Sale::where([
                ['payment_statut', '!=', 'paid'],
                ['customer_id', $this->customer_id],
            ])->get();

            $paid_amount_total = $this->amount;

            foreach($customer_sales_due as $key => $customer_sale) {
                if($paid_amount_total == 0)
                break;
                $due_amount = $customer_sale->GrandTotal - $customer_sale->paid_amount;

                if($paid_amount_total >= $due_amount) {
                    $amount = $due_amount;
                    $payment_status = Sale::PaymentPaid;
                }else {
                    $amount = $paid_amount_total;
                    $payment_status = Sale::PaymentPartial;
                }

                $payment_sale = new SalePayment;
                $payment_sale->sale_id = $customer_sale->id;
                $payment_sale->reference = app('App\Http\Controllers\PaymentSalesController')->getNumberOrder();
                $payment_sale->date = Carbon::now();
                $payment_sale->amount = $amount;
                $payment_sale->change = 0;
                $payment_sale->notes = $this['notes'];
                $payment_sale->user_id = Auth::user()->id;
                $payment_sale->save();

                $customer_sale->paid_amount += $amount;
                $customer_sale->payment_statut = $payment_status;
                $customer_sale->save();

                $paid_amount_total -= $amount;
            }
        }
    }

    public function render()
    {
        return view('livewire.customers.pay-due');
    }
}
