<?php

namespace App\Http\Livewire\Adjustment;

use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductTable extends Component
{
    use LivewireAlert;

    protected $listeners = ['productSelected'];

    public $products;
    public $hasAdjustments;

    public function mount($adjustedProducts = null)
    {
        $this->products = [];

        if ($adjustedProducts) {
            $this->hasAdjustments = true;
            $this->products = $adjustedProducts;
        } else {
            $this->hasAdjustments = false;
        }
    }

    public function render()
    {
        return view('livewire.adjustment.product-table');
    }

    public function productSelected($product)
    {
        switch ($this->hasAdjustments) {
            case true:
                if (in_array($product, array_map(function ($adjustment) {
                    return $adjustment['product'];
                }, $this->products))) {
                    $this->alert('error', 'Product already added');
                    return;
                }
                break;
            case false:
                if (in_array($product, $this->products)) {
                    $this->alert('error', 'Already exists in the product list!');
                    return;
                }
                break;
            default:
                $this->alert('error', 'Something went wrong!');
                return;
        }

        array_push($this->products, $product);
    }

    public function removeProduct($key)
    {
        unset($this->products[$key]);
    }
}
