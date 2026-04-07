<?php

declare(strict_types=1);

namespace App\Livewire\Adjustment;

use App\Traits\WithAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductTable extends Component
{
    use WithAlert;

    public mixed $products;

    public mixed $hasAdjustments;

    public function mount(mixed $adjustedProducts = null): void
    {
        $this->products = [];

        if ($adjustedProducts) {
            $this->hasAdjustments = true;
            $this->products = $adjustedProducts;
        } else {
            $this->hasAdjustments = false;
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.adjustment.product-table');
    }

    #[On('productSelected')]
    public function productSelected(mixed $product): void
    {
        switch ($this->hasAdjustments) {
            case true:
                if (in_array($product, array_map(static fn (array $adjustment) => $adjustment['product'], $this->products))) {
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

    public function removeProduct(int|string $key): void
    {
        unset($this->products[$key]);
    }
}
