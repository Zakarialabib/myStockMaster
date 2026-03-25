<?php

declare(strict_types=1);

namespace App\Livewire\PurchaseReturn;

use App\Actions\Purchases\UpdatePurchaseReturnAction;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Traits\LivewireCartTrait;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireCartTrait;
    use WithAlert;

    public $purchasereturn;

    #[Validate('required')]
    public $supplier_id;

    public $reference;

    #[Validate('required|numeric|min:0|max:100')]
    public $tax_percentage;

    #[Validate('required|numeric|min:0|max:100')]
    public $discount_percentage;

    #[Validate('required|numeric')]
    public $shipping_amount;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('required|numeric')]
    public $paid_amount;

    #[Validate('required|string|max:255')]
    public $status;

    #[Validate('required|string|max:255')]
    public $payment_method;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public $date;

    public function mount($id, string $cartInstance = 'purchase_return'): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->purchasereturn = PurchaseReturn::findOrFail($id);

        $this->supplier_id = $this->purchasereturn->supplier_id;
        $this->reference = $this->purchasereturn->reference;
        $this->tax_percentage = $this->purchasereturn->tax_percentage;
        $this->discount_percentage = $this->purchasereturn->discount_percentage;
        $this->shipping_amount = $this->purchasereturn->shipping_amount / 100;
        $this->total_amount = $this->purchasereturn->total_amount / 100;
        $this->paid_amount = $this->purchasereturn->paid_amount / 100;
        $this->status = $this->purchasereturn->status;
        $this->payment_method = $this->purchasereturn->payment_method;
        $this->note = $this->purchasereturn->note;
        $this->date = $this->purchasereturn->date;

        $this->clearCart();

        foreach ($this->purchasereturn->purchaseReturnDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);
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
        $this->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float
    {
        return $this->cartTotal + (float) $this->shipping_amount;
    }

    public function proceed(): void
    {
        if ($this->supplier_id !== null) {
            $this->update();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function update(): void
    {
        abort_if(Gate::denies('purchase_update'), 403);

        $this->validate();

        app(UpdatePurchaseReturnAction::class)(
            $this->purchasereturn,
            [
                'date' => $this->date,
                'reference' => $this->reference,
                'supplier_id' => $this->supplier_id,
                'tax_percentage' => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount' => $this->shipping_amount,
                'paid_amount' => $this->paid_amount,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
                'payment_method' => $this->payment_method,
                'note' => $this->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Purchase Return updated successfully.'));
        $this->redirectRoute('purchase-returns.index', navigate: true);
    }

    public function render()
    {
        $suppliers = Supplier::select(['id', 'name'])->get();

        return view('livewire.purchase-return.edit', [
            'cart_items' => $this->cartContent,
            'suppliers' => $suppliers,
        ]);
    }
}
