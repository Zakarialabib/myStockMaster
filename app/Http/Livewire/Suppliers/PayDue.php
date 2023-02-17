<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\PurchasePayment;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Enums\PaymentStatus;
use Carbon\Carbon;

class PayDue extends Component
{
    // get customer id
    // pay due amount

    public $amount;
    public $supplier_id;
    public $payment_method;
    
    
    public function getPurchasesSupplierDueProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
                   ->where('due_amount', '>', 0)
                   ->get();
    }

    public function makePayment()
    {
        $this->validate([
            'selectedSales' => 'required|array',
            'amount'        => 'required|numeric|min:0',
        ]);

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
        }
    }


    public function render()
    {
        return view('livewire.suppliers.pay-due');
    }
}
