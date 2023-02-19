<?php

declare(strict_types=1);

namespace App\Http\Livewire\Settings;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ApiToken extends Component
{
    public $token;
    public $ecomToken;

    public $custom_store_url;

    public $missingProducts;

    public function mount()
    {
        $this->custom_store_url = settings()->custom_store_url;

        $this->ecomToken = settings()->custom_api_key;

        $this->product_count = settings()->custom_products;
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
            'Authorization' => 'Bearer '.settings()->custom_api_key,
        ])->get(settings()->custom_store_url.'/api/products');

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
