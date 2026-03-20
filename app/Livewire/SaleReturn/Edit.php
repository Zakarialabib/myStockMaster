<?php

declare(strict_types=1);

namespace App\Livewire\SaleReturn;

use App\Actions\Sales\UpdateSaleReturnAction;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Traits\LivewireCartTrait;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Traits\WithAlert;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithAlert;
    use LivewireCartTrait;

    public $salereturn;

    #[Validate('required')]
    public $customer_id;

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

    public function mount($id, string $cartInstance = 'sale_return'): void
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        $this->salereturn = SaleReturn::findOrFail($id);

        $this->customer_id = $this->salereturn->customer_id;
        $this->reference = $this->salereturn->reference;
        $this->tax_percentage = $this->salereturn->tax_percentage;
        $this->discount_percentage = $this->salereturn->discount_percentage;
        $this->shipping_amount = $this->salereturn->shipping_amount / 100;
        $this->total_amount = $this->salereturn->total_amount / 100;
        $this->paid_amount = $this->salereturn->paid_amount / 100;
        $this->status = $this->salereturn->status;
        $this->payment_method = $this->salereturn->payment_method;
        $this->note = $this->salereturn->note;
        $this->date = $this->salereturn->date;

        $this->clearCart();

        foreach ($this->salereturn->saleReturnDetails as $detail) {
            $product = Product::findOrFail($detail->product_id);
            $this->addToCart([
                'id'         => $detail->product_id,
                'name'       => $detail->name,
                'quantity'   => $detail->quantity,
                'price'      => $detail->price / 100,
                'attributes' => [
                    'product_discount'      => $detail->discount_amount / 100,
                    'product_discount_type' => $detail->discount_type,
                    'sub_total'             => $detail->sub_total / 100,
                    'code'                  => $detail->code,
                    'stock'                 => $product->quantity,
                    'product_tax'           => $detail->tax_amount / 100,
                    'unit_price'            => $detail->unit_price / 100,
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
        if ($this->customer_id !== null) {
            $this->update();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function update(): void
    {
        abort_if(Gate::denies('sale_return_update'), 403);

        $this->validate();

        app(UpdateSaleReturnAction::class)(
            $this->salereturn,
            [
                'date'                => $this->date,
                'reference'           => $this->reference,
                'customer_id'         => $this->customer_id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount,
                'paid_amount'         => $this->paid_amount,
                'total_amount'        => $this->total_amount,
                'status'              => $this->status,
                'payment_method'      => $this->payment_method,
                'note'                => $this->note,
            ],
            $this->cartContent->toArray(),
            $this->cartTax,
            $this->cartDiscount
        );

        $this->clearCart();

        $this->alert('success', __('Sale Return updated successfully.'));
        $this->redirectRoute('sale-returns.index', navigate: true);
    }

    public function render()
    {
        $customers = Customer::select(['id', 'name'])->get();

        return view('livewire.sale-return.edit', [
            'cart_items' => $this->cartContent,
            'customers'  => $customers,
        ]);
    }
}
