<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Livesearch extends Component
{

    public $searchQuery = '';

    public $product;
    public $customer;
    public $supplier;
    public $sale;
    public $purchase;

    protected $queryString = ["searchQuery" => ["except" => "", "as" => "q"]];

    public function updatedSearchQuery()
    {
        $this->product = Product::query()->where('name', 'LIKE', '%' . $this->searchQuery . '%')->orWhere('code', 'like', '%' . $this->searchQuery . '%')->get();

        $this->customer = Customer::query()->where('name', 'LIKE', '%' . $this->searchQuery . '%')->get();

        $this->supplier = Supplier::query()->where('name', 'LIKE', '%' . $this->searchQuery . '%')->get();

        $this->sale = Sale::query()->where('reference', 'like', '%' . $this->searchQuery . '%')->get();

        $this->purchase = Purchase::query()->where('reference', 'like', '%' . $this->searchQuery . '%')->get();
    }

    public function render()
    {
        return view('livewire.livesearch');
    }
}
