<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Livewire\Forms\PurchaseReturnForm;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Services\PurchaseReturnService;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireCartTrait;
    use WithAlert;

    public PurchaseReturnForm $form;

    public mixed $purchasereturn;

    public function mount(mixed $id, string $cartInstance = 'purchase_return'): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->purchasereturn = PurchaseReturn::query()->findOrFail($id);

        $this->form->supplier_id = $this->purchasereturn->supplier_id;
        $this->form->reference = $this->purchasereturn->reference;
        $this->form->tax_percentage = $this->purchasereturn->tax_percentage;
        $this->form->discount_percentage = $this->purchasereturn->discount_percentage;
        $this->form->shipping_amount = $this->purchasereturn->shipping_amount / 100;
        $this->form->total_amount = $this->purchasereturn->total_amount / 100;
        $this->form->paid_amount = $this->purchasereturn->paid_amount / 100;
        $this->form->status = $this->purchasereturn->status;
        $this->form->payment_method = $this->purchasereturn->payment_method;
        $this->form->note = $this->purchasereturn->note;
        $this->form->date = $this->purchasereturn->date;

        $this->clearCart();

        foreach ($this->purchasereturn->purchaseReturnDetails as $detail) {
            $product = Product::query()->findOrFail($detail->product_id);
            $this->addToCart([
                'id' => $detail->product_id,
                'name' => $detail->name,
                'quantity' => $detail->quantity,
                'price' => $detail->price / 100,
                'attributes' => [
                    'product_discount' => $detail->product_discount_amount / 100,
                    'product_discount_type' => $detail->product_discount_type,
                    'sub_total' => $detail->sub_total / 100,
                    'code' => $detail->code,
                    'stock' => $product->quantity,
                    'product_tax' => $detail->product_tax_amount / 100,
                    'unit_price' => $detail->unit_price / 100,
                ],
            ]);
        }
    }

    public function hydrate(): void
    {
        $this->form->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float
    {
        return $this->cartTotal + (float) $this->form->shipping_amount;
    }

    public function proceed(): void
    {
        if ($this->form->supplier_id !== null) {
            $this->update();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function update(): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->form->validate();

        resolve(PurchaseReturnService::class)->update(
            $this->purchasereturn,
            [
                'date' => $this->form->date,
                'reference' => $this->form->reference,
                'supplier_id' => $this->form->supplier_id,
                'tax_percentage' => $this->form->tax_percentage,
                'discount_percentage' => $this->form->discount_percentage,
                'shipping_amount' => $this->form->shipping_amount,
                'paid_amount' => $this->form->paid_amount,
                'total_amount' => $this->form->total_amount,
                'status' => $this->form->status,
                'payment_method' => $this->form->payment_method,
                'note' => $this->form->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Purchase Return updated successfully.'));
        $this->redirectRoute('purchase-returns.index', navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $suppliers = Supplier::query()->select(['id', 'name'])->get();

        return view('livewire.purchase-return.edit', [
            'cart_items' => $this->cartContent,
            'suppliers' => $suppliers,
        ]);
    }
}
