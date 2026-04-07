<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\ProductWarehouse;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class PromoPrices extends Component
{
    use WithAlert;

    public mixed $percentage;

    public mixed $copyPriceToOldPrice;

    public bool $promoModal = false;

    #[On('promoModal')]
    public function promoModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->promoModal = true;
    }

    public function update(): void
    {
        $warehouseProducts = ProductWarehouse::query()->where('is_ecommerce', true)->get();

        foreach ($warehouseProducts as $warehouseProduct) {
            if ($this->copyPriceToOldPrice) {
                $warehouseProduct->old_price = $warehouseProduct->price;
            } else {
                $warehouseProduct->price *= 1 - $this->percentage / 100;
                $warehouseProduct->save();
            }
        }
    }

    public function render(): View|Factory
    {
        return view('livewire.products.promo-prices');
    }
}
