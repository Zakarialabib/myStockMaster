<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Models\SalePayment;
use App\Models\Sale;
use Auth;
use Carbon\Carbon;
use Livewire\Component;
use App\Enums\PaymentStatus;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayDue extends Component
{
    
    use LivewireAlert;

    public $amount;
    public $selectedSales;
    public $customer_id;
    public $payModal = false;

    public $listeners = ['editModal'];

    protected $rules = [
        'selectedSales' => 'required|array',
        'amount'        => 'required|numeric|min:0',
    ];
    public function getSalesCustomerDueProperty()
    {
        return Sale::where('customer_id', $this->customer_id)
                ->where('due_amount', '>', 0)
                ->get();
    }
    public function payModal($customer)
    {
        $this->payModal = true;
        $this->customer_id = $customer;

    }
    public function makePayment()
    {
        $this->validate();

        foreach ($this->selectedSales as $saleId) {
            $sale = Sale::findOrFail($saleId);
            $dueAmount = $sale->due_amount;
            $paidAmount = min($this->amount, $dueAmount);

            SalePayment::create([
                'date'           => now()->format('Y-m-d'),
                'reference'      => settings()->salepayment_prefix.'-'.date('Y-m-d-h'),
                'amount'         => $paidAmount,
                'sale_id'        => $sale->id,
                'payment_method' => $this->payment_method,
            ]);

            $sale->update([
                'paid_amount'    => ($sale->paid_amount + $paidAmount) * 100,
                'due_amount'     => max(0, $dueAmount - $paidAmount) * 100,
                'payment_status' => max(0, $dueAmount - $paidAmount) == 0 ? PaymentStatus::Paid : PaymentStatus::Partial,
            ]);

            $this->amount -= $paidAmount;

            if ($this->amount == 0) {
                break;
            }

            $this->alert('succes', '');
            $this->payModal = false;
        }
    }

    public function render()
    {
        return view('livewire.customers.pay-due');
    }
}
