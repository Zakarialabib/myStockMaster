<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\ProductWarehouse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Traits\WithAlert;

class PromoPrices extends Component
{
    use WithAlert;
    public $percentage;

    public $copyPriceToOldPrice;

    public $promoModal = false;

    protected $listeners = [
        'promoModal',
    ];

    public function promoModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->promoModal = true;
    }

    public function update(): void
    {
        $warehouseProducts = ProductWarehouse::where('is_ecommerce', true)->get();

        foreach ($warehouseProducts as $warehouse) {
            if ($this->copyPriceToOldPrice) {
                $warehouse->old_price = $warehouse->price;
            } else {
                $warehouse->price *= 1 - $this->percentage / 100;
                $warehouse->save();
            }
        }
    }

    public function render(): View|Factory
    {
        return view('livewire.products.promo-prices');
    }
}
