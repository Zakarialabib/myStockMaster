<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Enums\PurchaseStatus;
use App\Livewire\Forms\PurchaseForm;
use App\Livewire\Utils\WithModels;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\PurchaseService;
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
    use WithModels;

    public PurchaseForm $form;

    public Purchase $purchase;

    public mixed $purchase_details;

    public mixed $products;

    public mixed $product;

    public mixed $quantity;

    public mixed $reference;

    public mixed $check_quantity;

    public mixed $price;

    public mixed $discount_type;

    public mixed $item_discount;

    public array $listsForFields = [];

    public function mount(mixed $id, string $cartInstance = 'purchase'): void
    {
        $this->purchase = Purchase::query()->findOrFail($id);

        $this->purchase_details = $this->purchase->purchaseDetails;

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        foreach ($this->purchase_details as $purchase_detail) {
            $product = Product::query()->findOrFail($purchase_detail->product_id);
            $this->addToCart([
                'id' => $purchase_detail->product_id,
                'name' => $purchase_detail->name,
                'quantity' => $purchase_detail->quantity,
                'price' => $purchase_detail->price / 100,
                'attributes' => [
                    'product_discount' => $purchase_detail->product_discount_amount / 100,
                    'product_discount_type' => $purchase_detail->product_discount_type,
                    'sub_total' => $purchase_detail->sub_total / 100,
                    'code' => $purchase_detail->code,
                    'stock' => $product->quantity,
                    'product_tax' => $purchase_detail->product_tax_amount / 100,
                    'unit_price' => $purchase_detail->unit_price / 100,
                ],
            ]);
        }

        $this->reference = $this->purchase->reference;

        $this->form->setPurchase($this->purchase);
    }

    public function update(): void
    {
        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->form->validate();

        if (in_array($this->purchase->status, [PurchaseStatus::COMPLETED, PurchaseStatus::RETURNED, PurchaseStatus::CANCELED])) {
            $this->alert('error', __('Cannot update a completed, returned or canceled purchase.'));

            return;
        }

        $purchaseService = resolve(PurchaseService::class);
        $purchaseService->update(
            $this->purchase,
            [
                'date' => $this->form->date,
                'supplier_id' => $this->form->supplier_id,
                'warehouse_id' => $this->form->warehouse_id,
                'tax_percentage' => $this->form->tax_percentage,
                'discount_percentage' => $this->form->discount_percentage,
                'shipping_amount' => $this->form->shipping_amount,
                'paid_amount' => $this->form->paid_amount,
                'total_amount' => $this->form->total_amount,
                'payment_method' => $this->form->payment_method,
                'note' => $this->form->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Purchase Updated succesfully !'));

        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('purchase update'), 403);

        return view('livewire.purchase.edit');
    }

    public function hydrate(): void
    {
        $this->form->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): mixed
    {
        return $this->cartTotal + $this->form->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
    }

    public function updatedFormWarehouseId(mixed $value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', warehouseId: (int) $value);
    }

    public function updatedFormStatus(mixed $value): void
    {
        $this->form->status = $value;
    }
}
