<?php

declare(strict_types=1);

namespace App\Livewire\Suppliers;

use App\Enums\PaymentStatus;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayDue extends Component
{
    use WithAlert;

    public mixed $amount;

    public mixed $supplier_id;

    public mixed $payment_method;

    public mixed $selectedPurchases;

    public mixed $due_amount;

    public mixed $paid_amount;

    public function getPurchasesSupplierDueProperty()
    {
        return Purchase::query()->where('supplier_id', $this->supplier_id)
            ->where('due_amount', '>', 0)
            ->get();
    }

    public function makePayment(): void
    {
        $this->validate([
            'selectedPurchases' => 'required|array',
            'amount' => 'required|numeric|min:0',
        ]);

        foreach ($this->selectedPurchases as $selectedPurchase) {
            $purchase = Purchase::query()->findOrFail($selectedPurchase);
            $dueAmount = $purchase->due_amount;
            $paidAmount = min($this->amount, $dueAmount);

            PurchasePayment::query()->create([
                'date' => date('Y-m-d'),
                'amount' => $paidAmount,
                'purchase_id' => $purchase->id,
                'payment_method' => $this->payment_method,
                'user_id' => Auth::user()->id,
            ]);

            $purchase->update([
                'paid_amount' => ($purchase->paid_amount + $paidAmount) * 100,
                'due_amount' => max(0, $dueAmount - $paidAmount) * 100,
                'payment_status' => max(0, $dueAmount - $paidAmount) === 0 ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
            ]);

            $this->amount -= $paidAmount;

            if ($this->amount === 0) {
                break;
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.suppliers.pay-due');
    }
}
