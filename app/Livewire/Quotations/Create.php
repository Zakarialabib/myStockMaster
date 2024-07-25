<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Utils\WithModels;
use Livewire\Component;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    use LivewireAlert;
    use WithModels;
    public $cart_instance = 'quotation';

    #[Validate('required')]
    public $customer_id;

    #[Validate('required')]
    public $warehouse_id;

    // #[Rule('numeric')]
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

    public function proceed(): void
    {
        if ($this->customer_id !== null) {
            $this->store();
        } else {
            $this->alert('error', __('Please select a customer!'));
        }
    }

    public function mount(): void
    {
        abort_if(Gate::denies('quotation_create'), 403);

        Cart::instance('quotation')->destroy();

        $this->discount_percentage = 0;
        $this->tax_percentage = 0;
        $this->shipping_amount = 0;

        if (settings()->default_client_id !== null) {
            $this->customer_id = settings()->default_client_id;
        }

        if (settings()->default_warehouse_id !== null) {
            $this->warehouse_id = settings()->default_warehouse_id;
        }

        $this->date = date('Y-m-d');
    }

    public function store()
    {
        if ( ! $this->warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        DB::transaction(function (): void {
            $this->validate();

            $quotation = Quotation::create([
                'date'                => $this->date,
                'customer_id'         => $this->customer_id,
                'warehouse_id'        => $this->warehouse_id,
                'user_id'             => Auth::user()->id,
                'tax_percentage'      => $this->tax_percentage,
                'discount_percentage' => $this->discount_percentage,
                'shipping_amount'     => $this->shipping_amount * 100,
                'total_amount'        => $this->total_amount * 100,
                'status'              => $this->status,
                'note'                => $this->note,
                'tax_amount'          => (int) Cart::instance('quotation')->tax() * 100,
                'discount_amount'     => (int) Cart::instance('quotation')->discount() * 100,
            ]);

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id'            => $quotation->id,
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

        $this->alert('success', __('Quotation created successfully!'));

        return redirect()->route('quotations.index');
    }

    public function hydrate(): void
    {
        $this->total_amount = $this->calculateTotal();
    }

    public function calculateTotal(): float|int|array
    {
        return Cart::instance($this->cart_instance)->total() + $this->shipping_amount;
    }

    public function render()
    {
        abort_if(Gate::denies('quotation_create'), 403);

        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.quotations.create', ['cart_items' => $cart_items]);
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }
}
