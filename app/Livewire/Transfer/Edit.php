<?php

declare(strict_types=1);

namespace App\Livewire\Transfer;

use App\Livewire\Forms\TransferForm;
use App\Livewire\Utils\WithModels;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Transfer;
use App\Services\TransferService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithAlert;
    use WithModels;

    public TransferForm $form;

    public Transfer $transfer;

    public array $products = [];

    public mixed $hasTransfers;

    public function mount(int|string $id): void
    {
        $this->transfer = Transfer::query()->with('transferDetails', 'transferDetails.product')
            ->where('id', $id)->first();

        $this->form->date = $this->transfer->date;
        $this->form->from_warehouse_id = $this->transfer->from_warehouse_id;
        $this->form->to_warehouse_id = $this->transfer->to_warehouse_id;
        $this->form->reference = $this->transfer->reference;
        $this->form->note = $this->transfer->note;
        $this->form->status = $this->transfer->status;
        $this->form->total_qty = $this->transfer->total_qty;
        $this->form->total_cost = $this->transfer->total_cost;
        $this->form->total_amount = $this->transfer->total_amount;
        $this->form->shipping_amount = $this->transfer->shipping;
        $this->form->document = $this->transfer->document;
        $this->form->user_id = $this->transfer->user_id;

        $this->products = $this->transfer->transferDetails->map(fn ($detail) => [
            'id' => $detail->product_id,
            'name' => $detail->product->name,
            'code' => $detail->product->code,
            'quantities' => $detail->quantity,
            'price' => $detail->product->price ?? 0,
            'cost' => $detail->product->cost ?? 0,
        ])->all();
    }

    public function update(TransferService $transferService)
    {
        abort_if(Gate::denies('transfer_update'), 403);

        $this->form->validate();

        $transferService->updateTransfer($this->transfer, $this->form->all(), $this->products);

        return to_route('transfers.index');
    }

    #[On('productSelected')]
    public function productSelected(mixed $productId, ?int $warehouseId = null): void
    {
        $product = Product::query()->findOrFail($productId);

        $productWarehouse = ProductWarehouse::query()->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId ?? $this->form->from_warehouse_id)
            ->first();

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'price' => $productWarehouse instanceof ProductWarehouse ? $productWarehouse->price : ($product->price ?? 0),
            'cost' => $product->cost ?? 0,
            'barcode_symbology' => $product->barcode_symbology,
            'quantities' => 1,
        ];

        if (in_array($productData['id'], array_column($this->products, 'id'))) {
            $this->alert('error', __('Already exists in the product list!'));

            return;
        }

        $this->products[] = $productData;
        $this->calculateTotal();
    }

    public function removeProduct(int|string $key): void
    {
        unset($this->products[$key]);
        $this->calculateTotal();
    }

    public function updateQuantity(int|string $key, int|float $quantity): void
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

    #[On('warehouseSelected')]
    public function updatedFormFromWarehouseId(mixed $value): void
    {
        $this->form->from_warehouse_id = $value;
        $this->dispatch('warehouseSelected', $this->form->from_warehouse_id);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.transfer.edit');
    }
}
