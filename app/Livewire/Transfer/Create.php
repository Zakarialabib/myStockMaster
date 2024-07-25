<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Utils\WithModels;
use App\Models\TransferDetails;
use App\Models\Transfer;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Throwable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    use LivewireAlert;
    use WithModels;

    #[Validate('required|date')]
    public $date;

    #[Validate('nullable|string|max:1000')]
    public $note;

    #[Validate('required|string|max:255')]
    public $reference;

    public $total_qty;

    public $total_cost;

    public $total_amount;

    public $shipping_amount;

    public $from_warehouse_id;

    public $to_warehouse_id;

    public $products;

    public $document;

    public $hasTransfers;

    public function mount(): void
    {
        $this->products = [];

        $this->reference = 'Adj-'.Str::random(5);
        $this->date = date('Y-m-d');

        if (settings()->default_warehouse_id !== null) {
            $this->from_warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        return view('livewire.transfer.create');
    }

    public function updatedFromWarehouseId($value): void
    {
        $this->from_warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->from_warehouse_id);
    }

    public function store()
    {
        abort_if(Gate::denies('transfer_create'), 403);

        if ( ! $this->from_warehouse_id) {
            $this->alert('error', __('Please select a warehouse'));

            return;
        }

        try {
            $this->validate();

            $transfer = Transfer::create([
                'reference'         => $this->reference,
                'date'              => $this->date,
                'user_id'           => auth()->id(),
                'from_warehouse_id' => $this->from_warehouse_id,
                'total_qty'         => $this->total_qty,
                'total_cost'        => $this->total_cost,
                'total_amount'      => $this->total_amount,
                'shipping_amount'   => $this->shipping_amount,
                'document'          => $this->document,
                'status'            => true,
                'note'              => $this->note,
            ]);

            foreach ($this->products as $product) {
                TransferDetails::create([
                    'transfer_id'  => $transfer->id,
                    'product_id'   => $product['id'],
                    'warehouse_id' => $this->to_warehouse_id,
                    'quantity'     => $product['quantities'],
                ]);

                $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
                    ->where('warehouse_id', $this->from_warehouse_id)
                    ->first();

                // change from from_warehouse to to_warehouse
                if ($productWarehouse) {
                    $productWarehouse->update([
                        'warehouse_id' => $this->to_warehouse_id,
                    ]);
                }
            }

            $this->alert('success', __('Transfer created successfully'));

            return redirect()->route('transfers.index');
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error Occurred in '.$throwable->getMessage());
        }
    }

    #[On('productSelected')]
    public function productSelected(array $product): void
    {
        switch ($this->hasTransfers) {
            case true:
                if (in_array($product, array_map(static fn ($transfer) => $transfer['product'], $this->products))) {
                    $this->alert('error', __('Product added succesfully'));

                    return;
                }

                break;
            case false:
                if (in_array($product, $this->products)) {
                    $this->alert('error', __('Already exists in the product list!'));

                    return;
                }

                break;
            default:
                $this->alert('error', __('Something went wrong!'));

                return;
        }

        $this->products[] = $product;
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
    }
}
