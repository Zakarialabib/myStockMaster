<?php

namespace App\Http\Livewire\Settings;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Livewire\Component;


class ApiToken extends Component
{
    public $token;
    
    public $custom_store_url;
    public $custom_api_key;

    public $missingProducts;
    public $inventoryProducts;
    public $woocommerce_api_key;
    public $woocommerce_store_url;
    public $shopify_api_key;
    public $shopify_store_url;

    /** @var array<string> */
    public $listeners = ['refreshIndex' => '$refresh'];

    public function mount()
    {

        $this->woocommerce_api_key = settings()->woocommerce_api_key;
        $this->woocommerce_store_url = settings()->woocommerce_store_url;

        $this->shopify_api_key = settings()->shopify_api_key;
        $this->shopify_store_url = settings()->shopify_store_url;
      
        $this->custom_store_url = settings()->custom_store_url;
        $this->custom_api_key = settings()->custom_api_key;

        $this->missingProducts = settings()->custom_products ;

        $this->inventoryProducts = Product::count();
    }

    public function createToken()
    {
        $this->resetErrorBag();

        $abilities = ['read', 'write'];

        $token = auth()->user()->createToken('inventory', $abilities)->plainTextToken;

        $this->token = $token;
    }

    public function deleteToken()
    {
        auth()->user()->tokens()->delete();
        $this->token = null;
    }

    public function countNotExistingProducts()
    {
        $inventoryProducts = Product::pluck('code')->toArray();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . settings()->custom_api_key,
        ])->get(settings()->custom_store_url . '/api/products');

        // dd(settings()->custom_api_key);
        $ecomProducts = $response->json()['data'];

        $missingProducts = array_diff($inventoryProducts, array_column($ecomProducts, 'code'));
        
        $this->missingProducts = count($missingProducts);

        settings()->update([
            'custom_products' => $this->missingProducts,
        ]);

        return $this->missingProducts;
    }

    public function render()
    {
        return view('livewire.settings.api-token');
    }
}
