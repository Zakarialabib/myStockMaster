<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Forms\TransferForm;
use App\Livewire\Utils\WithModels;
use App\Models\ProductWarehouse;
use App\Models\Transfer;
use App\Models\TransferDetails;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithAlert;
    use WithModels;

    public TransferForm $form;

    public $products;

    public $hasTransfers;

    public function mount(): void
    {
        $this->products = [];

        $this->form->reference = 'TR-' . Str::random(5);
        $this->form->date = date('Y-m-d');
        $this->form->status = 1;

        if (settings()->default_warehouse_id !== null) {
            $this->form->from_warehouse_id = settings()->default_warehouse_id;
        }
    }

    public function render()
    {
        return view('livewire.transfer.create');
    }

    public function updatedFormFromWarehouseId($value): void
    {
        $this->form->from_warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->from_warehouse_id);
    }

    public function store()
    {
        abort_if(Gate::denies('transfer_create'), 403);

        if (! $this->form->from_warehouse_id) {
            $this->alert('error', __('Please select a from warehouse'));

            return;
        }

        if (! $this->form->to_warehouse_id) {
            $this->alert('error', __('Please select a to warehouse'));

            return;
        }

        if ($this->form->from_warehouse_id === $this->form->to_warehouse_id) {
            $this->alert('error', __('From warehouse and To warehouse cannot be the same'));

            return;
        }

        try {
            $this->form->validate();

            $transfer = Transfer::create([
                'reference' => $this->form->reference,
                'date' => $this->form->date,
                'user_id' => auth()->id(),
                'from_warehouse_id' => $this->form->from_warehouse_id,
                'to_warehouse_id' => $this->form->to_warehouse_id,
                'total_qty' => $this->form->total_qty,
                'item' => count($this->products),
                'total_tax' => 0,
                'total_cost' => $this->form->total_cost,
                'total_amount' => $this->form->total_amount,
                'shipping' => $this->form->shipping_amount,
                'document' => $this->form->document,
                'status' => $this->form->status,
                'note' => $this->form->note,
            ]);

            foreach ($this->products as $product) {
                TransferDetails::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $product['id'],
                    'warehouse_id' => $this->form->to_warehouse_id,
                    'quantity' => $product['quantities'] ?? 1,
                ]);

                $qty = $product['quantities'] ?? 1;

                // Decrement the source ProductWarehouse
                ProductWarehouse::where('product_id', $product['id'])
                    ->where('warehouse_id', $this->form->from_warehouse_id)
                    ->decrement('qty', $qty);

                // Increment the destination ProductWarehouse
                $destProductWarehouse = ProductWarehouse::firstOrCreate(
                    [
                        'product_id' => $product['id'],
                        'warehouse_id' => $this->form->to_warehouse_id,
                    ],
                    [
                        'price' => $product['price'] ?? 0,
                        'cost' => $product['cost'] ?? 0,
                        'qty' => 0,
                    ]
                );

                $destProductWarehouse->increment('qty', $qty);
            }

            $this->alert('success', __('Transfer created successfully'));

            return redirect()->route('transfers.index');
        } catch (Throwable $throwable) {
            $this->alert('error', 'Error Occurred in ' . $throwable->getMessage());
        }
    }

    #[On('productSelected')]
    public function productSelected(array $product): void
    {
        $product['quantities'] = 1;

        if (in_array($product, $this->products)) {
            $this->alert('error', __('Already exists in the product list!'));

            return;
        }

        $this->products[] = $product;
        $this->calculateTotal();
    }

    public function removeProduct($key): void
    {
        unset($this->products[$key]);
        $this->calculateTotal();
    }

    public function updateQuantity($key, $quantity): void
    {
        $this->products[$key]['quantities'] = $quantity;
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->form->total_qty = 0;
        $this->form->total_cost = 0;
        $this->form->total_amount = 0;

        foreach ($this->products as $product) {
            $qty = $product['quantities'] ?? 1;
            $price = $product['price'] ?? 0;
            $cost = $product['cost'] ?? 0;

            $this->form->total_qty += $qty;
            $this->form->total_cost += $qty * $cost;
            $this->form->total_amount += $qty * $price;
        }
    }
}
