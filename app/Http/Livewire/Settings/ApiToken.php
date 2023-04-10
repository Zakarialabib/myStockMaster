<?php

declare(strict_types=1);

namespace App\Http\Livewire\Settings;

use App\Enums\IntegrationType;
use App\Models\Product;
use App\Models\Integration;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ApiToken extends Component
{
    public $token;

    public $integration;
    public $integrations;

    public $inventoryProducts;
    public $missingProducts;

    public $type;
    public $store_url;
    public $api_key;

    /** @var array<string> */
    public $listeners = ['refreshIndex' => '$refresh'];

    public function mount()
    {
        $this->integrations = Integration::select('id', 'store_url', 'last_sync', 'type', 'products', 'status')->get();
        $this->integration = Integration::where('type', IntegrationType::CUSTOM)->first();
        $this->missingProducts = $this->integration?->products;

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
            'Authorization' => 'Bearer '.$this->integration->api_key,
        ])->get($this->integration->store_url.'/api/products');

        $ecomProducts = $response->json()['data'];

        $missingProducts = array_diff($inventoryProducts, array_column($ecomProducts, 'code'));

        $this->missingProducts = count($missingProducts);

        $this->integration->update([
            'products' => $this->missingProducts,
        ]);

        return $this->missingProducts;
    }

    public function render()
    {
        return view('livewire.settings.api-token');
    }
}
