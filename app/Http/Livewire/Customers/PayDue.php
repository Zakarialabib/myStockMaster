<?php

declare(strict_types=1);

namespace App\Http\Livewire\Customers;

use App\Enums\PaymentStatus;
use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

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
                'date'           => date('Y-m-d'),
                'amount'         => $paidAmount,
                'sale_id'        => $sale->id,
                'payment_method' => $this->payment_method,
                'user_id'        => Auth::user()->id,
            ]);

            $sale->update([
                'paid_amount'    => ($sale->paid_amount + $paidAmount) * 100,
                'due_amount'     => max(0, $dueAmount - $paidAmount) * 100,
                'payment_status' => max(0, $dueAmount - $paidAmount) === 0 ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
            ]);

            $this->amount -= $paidAmount;

            if ($this->amount === 0) {
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
