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

    public $results = [];

    public $product;
    public $customer;
    public $supplier;
    public $sale;
    public $purchase;

    public function render()
    {
        return view('livewire.livesearch');
    }
    public function updatedSearchQuery()
    {
        $results = [];
        $models = [Product::class, Customer::class, Supplier::class, Sale::class, Purchase::class];

    foreach ($models as $model) {
        if ($model == Product::class) {
            $result = $model::query()->where('name', 'LIKE', '%'.$this->searchQuery.'%')->orWhere('code', 'like', '%'.$this->searchQuery.'%')->get();
        } elseif ($model == Customer::class) {
            $result = $model::query()->where('name', 'LIKE', '%'.$this->searchQuery.'%')->get();
        } elseif ($model == Supplier::class) {
            $result = $model::query()->where('name', 'LIKE', '%'.$this->searchQuery.'%')->get();
        } else {
            $result = $model::query()->where('reference', 'like', '%'.$this->searchQuery.'%')->get();
        }
        array_push($results, $result); 
    } 

    $this->product = $results[0];
    $this->customer = $results[1];
    $this->supplier = $results[2];
    $this->sale = $results[3];
    $this->purchase = $results[4];

    }


   
}
