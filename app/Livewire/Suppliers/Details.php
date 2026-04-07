<?php

declare(strict_types=1);

namespace App\Livewire\Suppliers;

use App\Livewire\Utils\Datatable;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class Details extends Component
{
    use Datatable;
    use WithAlert;

    #[Locked]
    public mixed $supplier_id;

    public string $model = Supplier::class;

    public mixed $warehouse_id;

    public mixed $supplier;

    public mixed $purchases;

    public function mount(int|string $id): void
    {
        $this->supplier = Supplier::query()->findOrFail($id);
        $this->supplier_id = $this->supplier->id;
    }

    #[Computed]
    public function TotalPurchases(): float
    {
        return $this->supplierSum('total_amount');
    }

    #[Computed]
    public function TotalPurchaseReturns(): float
    {
        return PurchaseReturn::query()->where('supplier_id', $this->supplier_id)
            ->sum('total_amount') / 100;
    }

    #[Computed]
    public function TotalDue(): float
    {
        return $this->supplierSum('due_amount');
    }

    #[Computed]
    public function TotalPayments(): float
    {
        return $this->supplierSum('paid_amount');
    }

    #[Computed]
    public function Debit(): float
    {
        // Step 1: Calculate total purchases revenue for completed purchases
        $purchasesTotal = Purchase::query()->where('supplier_id', $this->supplier_id)
            ->completed()
            ->sum('total_amount') / 100;

        // Step 2: Calculate total purchases returns
        $purchaseReturnsTotal = PurchaseReturn::query()->where('supplier_id', $this->supplier_id)
            ->completed()
            ->sum('total_amount') / 100;

        // Step 3: Calculate the total product cost from the pivot table
        $productCosts = 0;

        foreach ($this->purchases as $purchase) {
            foreach ($purchase->purchaseDetails as $purchaseDetail) {
                // Assuming you have a warehouses relationship defined on the Product model
                $productWarehouse = $purchaseDetail->product->warehouses->where('warehouse_id', $this->warehouse_id)->first();

                if ($productWarehouse) {
                    $productCosts += $productWarehouse->cost * $purchaseDetail->quantity;
                }
            }
        }

        // Step 4: Calculate profit
        $debit = ($purchasesTotal - $purchaseReturnsTotal) - $productCosts;

        return $debit;
    }

    #[Computed]
    public function purchases()
    {
        $query = Purchase::query()->where('supplier_id', $this->supplier_id)
            ->with('supplier')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function supplierPayments()
    {
        $query = Purchase::query()->where('supplier_id', $this->supplier_id)
            ->with('purchasepayments.purchase')
            ->advancedFilter([
                's' => $this->search ?: null,
                'order_column' => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        return $query->paginate($this->perPage);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.suppliers.details');
    }

    private function supplierSum(string $field): int|float
    {
        return Purchase::query()->whereBelongsTo($this->supplier)->sum($field) / 100;
    }
}
