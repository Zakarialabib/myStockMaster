<?php

declare(strict_types=1);

namespace App\Http\Livewire\Suppliers;

use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Details extends Component
{
    public $supplier_id;

    /** @var mixed */
    public $supplier;

    public function mount($supplier): void
    {
        $this->supplier = Supplier::find($supplier->id);
        $this->supplier_id = $this->supplier->id;
    }

    public function getTotalPurchasesProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
            ->sum('total_amount');
    }

    public function getTotalPurchaseReturnsProperty()
    {
        return PurchaseReturn::where('supplier_id', $this->supplier_id)
            ->sum('total_amount');
    }

    // total due amount
    public function getTotalDueProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
            ->sum('due_amount') / 100;
    }

    // show totalPayments
    public function getTotalPaymentsProperty()
    {
        return Purchase::where('supplier_id', $this->supplier_id)
            ->sum('paid_amount');
    }

    // show Debit
    public function getDebitProperty()
    {
        $purchases = Purchase::where('supplier_id', $this->supplier_id)
            ->completed()->sum('total_amount');
        $purchase_returns = PurchaseReturn::where('supplier_id', $this->supplier_id)
            ->completed()->sum('total_amount');

        $product_costs = 0;

        foreach (Purchase::completed()->with('purchaseDetails')->get() as $purchase) {
            foreach ($purchase->purchaseDetails as $purchaseDetail) {
                $product_costs += $purchaseDetail->product->cost;
            }
        }

        $debt = ($purchases - $purchase_returns) / 100;
        $debit = $debt - $product_costs;

        return $debit;
    }

    // show PurchaseInvoices
    // show PurchasePayments

    public function render(): View|Factory
    {
        return view('livewire.suppliers.details');
    }
}
