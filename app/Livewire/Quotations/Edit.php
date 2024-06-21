<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Utils\WithModels;
use Livewire\Component;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireAlert;
    use WithModels;

    public $quotation;

    public $quotation_details;

    public $reference;

    // public $cartItem;

    #[Validate('required')]
    public $customer_id;

    #[Validate('required')]
    public $warehouse_id;

    #[Validate('required|numeric')]
    public $total_amount;

    #[Validate('numeric')]
    public $shipping_amount;

    public $note;

    #[Validate('required|integer|max:255')]
    public $status;

    #[Validate('required')]
    public $date;

    #[Validate('integer|min:0|max:100')]
    public $tax_percentage;

    #[Validate('integer|min:0|max:100')]
    public $discount_percentage;

    public function mount($id): void
    {
        $this->quotation = Quotation::findOrFail($id);

        $this->quotation_details = $this->quotation->quotationDetails;

        Cart::instance('quotation')->destroy();

        $cart = Cart::instance('quotation');

        foreach ($this->quotation_details as $quotation_detail) {
            $cart->add([
                'id'      => $quotation_detail->product_id,
                'name'    => $quotation_detail->name,
                'qty'     => $quotation_detail->quantity,
                'price'   => $quotation_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount'      => $quotation_detail->product_discount_amount,
                    'product_discount_type' => $quotation_detail->product_discount_type,
                    'sub_total'             => $quotation_detail->sub_total,
                    'code'                  => $quotation_detail->code,
                    'stock'                 => Product::findOrFail($quotation_detail->product_id)->quantity,
                    'product_tax'           => $quotation_detail->product_tax_amount,
                    'unit_price'            => $quotation_detail->unit_price,
                ],
            ]);
        }

        $this->reference = $this->quotation->reference;
        $this->date = $this->quotation->date;
        $this->customer_id = $this->quotation->customer_id;
        $this->warehouse_id = $this->quotation->warehouse_id;
        $this->status = $this->quotation->status;
        $this->note = $this->quotation->note;
        $this->tax_percentage = $this->quotation->tax_percentage;
        $this->discount_percentage = $this->quotation->discount_percentage;
        $this->shipping_amount = $this->quotation->shipping_amount;
        $this->total_amount = $this->quotation->total_amount;
    }

    public function update()
    {
        DB::transaction(function (): void {
            foreach ($this->quotation->quotationDetails as $quotation_detail) {
                $quotation_detail->delete();
            }

            $this->quotation->update([
                'date'                => $this->date,
                'reference'           => $this->reference,
                'customer_id'         => $this->customer_id,
                'user_id'             => Auth::user()->id,
                'warehouse_id'        => $this->warehouse_id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount * 100,
                'total_amount'        => $this->total_amount * 100,
                'status'              => $this->status,
                'note'                => $this->note,
                'tax_amount'          => Cart::instance('quotation')->tax() * 100,
                'discount_amount'     => Cart::instance('quotation')->discount() * 100,
            ]);

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id'            => $this->quotation->id,
                    'product_id'              => $cart_item->id,
                    'name'                    => $cart_item->name,
                    'code'                    => $cart_item->options->code,
                    'quantity'                => $cart_item->qty,
                    'price'                   => $cart_item->price * 100,
                    'unit_price'              => $cart_item->options->unit_price * 100,
                    'sub_total'               => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type'   => $cart_item->options->product_discount_type,
                    'product_tax_amount'      => $cart_item->options->product_tax * 100,
                ]);
            }

            Cart::instance('quotation')->destroy();
        });

        $this->alert('success', __('Quotation updated Successfully!'));

        return redirect()->route('quotations.index');
    }

    public function render()
    {
        abort_if(Gate::denies('quotation update'), 403);

        return view('livewire.quotations.edit');
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }
}
