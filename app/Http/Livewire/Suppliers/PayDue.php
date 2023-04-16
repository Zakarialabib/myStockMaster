<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Enums\PaymentStatus;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayDue extends Component
{
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
            'selectedPurchases' => 'required|array',
            'amount'            => 'required|numeric|min:0',
        ]);

        foreach ($this->selectedPurchases as $purchaseId) {
            $purchase = Purchase::findOrFail($purchaseId);
            $dueAmount = $purchase->due_amount;
            $paidAmount = min($this->amount, $dueAmount);

            PurchasePayment::create([
                'date'           => date('Y-m-d'),
                'amount'         => $paidAmount,
                'purchase_id'    => $purchase->id,
                'payment_method' => $this->payment_method,
                'user_id'        => Auth::user()->id,
            ]);

            $purchase->update([
                'paid_amount'    => ($purchase->paid_amount + $paidAmount) * 100,
                'due_amount'     => max(0, $dueAmount - $paidAmount) * 100,
                'payment_status' => max(0, $dueAmount - $paidAmount) === 0 ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
            ]);

            $this->amount -= $paidAmount;

            if ($this->amount === 0) {
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.suppliers.pay-due');
    }
}
