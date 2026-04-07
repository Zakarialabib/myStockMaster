<?php

declare(strict_types=1);

namespace App\Livewire\Sales;

use App\Enums\SaleStatus;
use App\Livewire\Forms\SaleForm;
use App\Livewire\Utils\WithModels;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
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

    public SaleForm $form;

    public Sale $sale;

    public $products;

    public $product;

    public $quantity;

    public $reference;

    public $check_quantity;

    public $price;

    public $discount_type;

    public $item_discount;

    public $sale_details;

    public function mount($id, string $cartInstance = 'sale'): void
    {
        $this->sale = Sale::findOrFail($id);

        abort_if(Gate::denies('sale update'), 403);

        $this->sale_details = $this->sale->saleDetails;

        $this->cartInstance = $cartInstance;
        $this->initializeCart($cartInstance);

        foreach ($this->sale_details as $sale_detail) {
            $product = Product::findOrFail($sale_detail->product_id);
            $this->addToCart([
                'id' => $sale_detail->product_id,
                'name' => $sale_detail->name,
                'quantity' => $sale_detail->quantity,
                'price' => $sale_detail->price / 100,
                'attributes' => [
                    'product_discount' => $sale_detail->product_discount_amount / 100,
                    'product_discount_type' => $sale_detail->product_discount_type,
                    'sub_total' => $sale_detail->sub_total / 100,
                    'code' => $sale_detail->code,
                    'stock' => $product->quantity,
                    'product_tax' => $sale_detail->product_tax_amount / 100,
                    'unit_price' => $sale_detail->unit_price / 100,
                ],
            ]);
        }

        $this->reference = $this->sale->reference;

        $this->form->setSale($this->sale);
    }

    public function proceed(): void
    {
        if ($this->form->customer_id !== null) {
            $this->update();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function update(): void
    {
        if (! $this->form->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        $this->form->validate();

        if (in_array($this->sale->status, [SaleStatus::COMPLETED, SaleStatus::RETURNED, SaleStatus::CANCELED])) {
            $this->alert('error', __('Cannot update a completed, returned or canceled sale.'));

            return;
        }

        $saleService = app(SaleService::class);
        $saleService->update(
            $this->sale,
            [
                'date' => $this->form->date,
                'customer_id' => $this->form->customer_id,
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

        $this->alert('success', __('Sale Updated succesfully !'));

        $this->redirectRoute('sales.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.edit');
    }

    public function updatedFormWarehouseId($value): void
    {
        $this->form->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->warehouse_id);
    }

    public function updatedFormStatus($value): void
    {
        if ($value === (string) SaleStatus::COMPLETED->value || $value === SaleStatus::COMPLETED->value) {
            $this->form->paid_amount = $this->form->total_amount;
        }
    }
}
