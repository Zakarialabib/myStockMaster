<?php

declare(strict_types=1);

namespace App\Livewire\Purchase;

use App\Actions\Purchases\StorePurchaseAction;
use App\Enums\PurchaseStatus;
use App\Livewire\Utils\WithModels;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Purchase')]
class Create extends Component
{
    use LivewireCartTrait;
    use WithModels;

    public $cart_item;

    #[Validate('required')]
    public $warehouse_id;

    #[Validate('required')]
    public $supplier_id;

    #[Validate('required|integer|min:0|max:100')]
    public $tax_percentage;

    #[Validate('required|integer|min:0|max:100')]
    public $discount_percentage;

    #[Validate('required|numeric')]
    public $shipping_amount;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('required|numeric')]
    public $paid_amount;

    #[Validate('required')]
    public $status;

    #[Validate('required|string|max:50')]
    public $payment_method;

    public $payment_status;

    #[Validate('nullable|string|max:1000')]
    public $note;

    public $product;

    public $quantity;

    public $check_quantity;

    public $price;

    public $date;

    public $discount_type;

    public $item_discount;

    public array $listsForFields = [];

    public function mount(string $cartInstance = 'purchase'): void
    {
        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->tax_percentage = 0;
        $this->discount_percentage = 0;
        $this->shipping_amount = 0;
        $this->paid_amount = 0;
        $this->payment_method = 'cash';
        $this->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        // abort_if(Gate::denies('purchase_create'), 403);

        return view('livewire.purchase.create', [
            'cart_items' => $this->cartContent,
        ]);
    }

    public function hydrate(): void
    {
        $this->total_amount = $this->calculateTotal();
    }

    public function proceed(): void
    {
        if ($this->supplier_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a supplier!'));
        }
    }

    public function store(): void
    {
        if (! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->validate();

        app(StorePurchaseAction::class)(
            [
                'date' => $this->date,
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'user_id' => Auth::id(),
                'tax_percentage' => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount' => $this->shipping_amount,
                'paid_amount' => $this->paid_amount,
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'note' => $this->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount,
        );

        $this->alert('success', __('Purchase created successfully!'));

        $this->clearCart();

        $this->redirectRoute('purchases.index', navigate: true);
    }

    public function calculateTotal(): mixed
    {
        return $this->cartTotal + $this->shipping_amount;
    }

    public function resetCart(): void
    {
        $this->clearCart();
    }

    public function updatedWarehouseId($warehouse_id): void
    {
        $this->warehouse_id = $warehouse_id;
        $this->dispatch('warehouseSelected', $warehouse_id);
    }

    public function updatedStatus($status): void
    {
        if ($status === PurchaseStatus::COMPLETED) {
            $this->paid_amount = $this->total_amount;
        }
    }

    public function updatedPaymentMethod($payment_status): void
    {
        if ($payment_status === 'cash') {
            $this->paid_amount = $this->total_amount;
        }
    }
}
