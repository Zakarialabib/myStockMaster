<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Livewire\Component;

class Livesearch extends Component
{
    public $searchQuery = '';

    public $product;

    public $customer;

    public $supplier;

    public $sale;

    public $purchase;

    protected $queryString = [
        'searchQuery',
    ];

    public function mount(): void {}

    public function render()
    {
        return view('livewire.utils.livesearch', [
            'products' => Product::query()->searchByNameOrCode($this->searchQuery)->get(),

            'customers' => $this->customer = Customer::query()->searchByName($this->searchQuery)
                ->with('sales')->get(),

            'suppliers' => Supplier::query()->searchByName($this->searchQuery)
                ->with('purchases')->get(),

            'sales' => Sale::query()->searchByReference($this->searchQuery)->get(),

            'purchase' => Purchase::query()->searchByReference($this->searchQuery)->get(),
        ]);
    }
}
