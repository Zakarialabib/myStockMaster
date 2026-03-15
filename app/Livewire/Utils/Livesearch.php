<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Traits\WithAlert;

class Livesearch extends Component
{
    use WithAlert;
    #[Url]
    public string $searchQuery = '';

    public function render()
    {
        return view('livewire.utils.livesearch');
    }

    #[Computed]
    public function products()
    {
        return Product::query()->searchByNameOrCode($this->searchQuery)->get();
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()->searchByName($this->searchQuery)
            ->with('sales')->get();
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->searchByName($this->searchQuery)
            ->with('purchases')->get();
    }

    #[Computed]
    public function sales()
    {
        return Sale::query()->searchByReference($this->searchQuery)->get();
    }

    #[Computed]
    public function purchases()
    {
        return Purchase::query()->searchByReference($this->searchQuery)->get();
    }
}
