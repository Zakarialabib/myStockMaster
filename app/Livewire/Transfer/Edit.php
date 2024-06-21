<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Utils\WithModels;
use App\Models\TransferDetails;
use App\Models\Transfer;
use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Edit extends Component
{
    use LivewireAlert;
    use WithModels;

    public $transfer;

    public $date;

    #[Validate('nullable|string|max:1000')]
    public $note;

    #[Validate('required|string|max:255')]
    public $reference;

    #[Validate('required', message: 'Please provide warehouse')]
    public $warehouse_id;

    public $quantity;

    public $type;

    public $products;

    public $hasTransfers;

    protected $listeners = [
        'warehouseSelected' => 'updatedWarehouseId',
        'productSelected',
    ];

    protected $rules = [
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.type'     => 'required|in:add,sub',
    ];

    public function mount($id): void
    {
        $this->transfer = Transfer::with('transferDetails')
            ->where('id', $id)->first();

        $this->date = $this->transfer->date;
        $this->warehouse_id = $this->transfer->warehouse_id;

        $this->reference = $this->transfer->reference;

        $this->products = $this->transfer->adjustedProducts;
    }

    public function update()
    {
        abort_if(Gate::denies('transfer_update'), 403);

        $this->validate();

        $this->transfer->update([
            'reference'    => $this->reference,
            'note'         => $this->note,
            'date'         => $this->date,
            'user_id'      => auth()->id(),
            'warehouse_id' => $this->warehouse_id,
        ]);

        foreach ($this->products as $product) {
            TransferDetails::updateOrCreate(
                [
                    'transfer_id'  => $this->transfer->id,
                    'product_id'   => $product['product_id'],
                    'warehouse_id' => $product['warehouse_id'],
                    'quantity'     => $product['quantity'],
                    'type'         => $product['type'],
                ]
            );

            $productWarehouse = ProductWarehouse::where('product_id', $product['product_id'])
                ->where('warehouse_id', $product['warehouse_id'])
                ->first();

            if ($product['type'] === 'add') {
                $productWarehouse->update([
                    'qty' => $productWarehouse->qty + $product['quantity'],
                ]);
            } elseif ($product['type'] === 'sub') {
                $productWarehouse->update([
                    'qty' => $productWarehouse->qty - $product['quantity'],
                ]);
            }
        }

        return redirect()->route('transfers.index');
    }

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

        // add default quantity and type to the product array
        $product['quantity'] = 10;
        $product['type'] = 'add';

        $this->products[] = $product;
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
    }

    public function updatedWarehouseId($value): void
    {
        $this->warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->warehouse_id);
    }

    public function render()
    {
        return view('livewire.transfer.edit');
    }
}
