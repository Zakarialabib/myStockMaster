<?php

namespace App\Http\Livewire\Settings;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

class ApiToken extends Component
{
    public $token;
    public $ecomToken;

    public $custom_store_url;

    public $missingProducts;

    public $authenticated = false;

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
        // Reset the API key and authentication status
        $this->token = null;
        $this->authenticated = false;
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

    public function authenticate()
    {
        $url = $this->custom_store_url;

        $client = new Client();
        
        $response = $client->request('POST', $url.'/api/login', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'json' => [
                'email' => 'admin@gmail.com',
                'password' => 'password',
            ],
        ]);

        
        if ($response->getStatusCode() === Response::HTTP_OK) {
            $this->authenticated = true;
            $data = json_decode($response->getBody(), true);
            $ecommerceToken = $data['api_token'];

            settings()->update([
                'custom_store_url' => $this->custom_store_url,
                'custom_api_key' => $ecommerceToken,
                'custom_api_secret' => 'your-secret-value', // replace with your own secret value
                'custom_last_sync' => null, // set to null initially
                'custom_products' => null // set to null initially
            ]);
            
            session()->flash('success', 'Authentication successful!');
        } else {
            session()->flash('error', 'Authentication failed!');
        }
    }

    public function render()
    {
        return view('livewire.settings.api-token');
    }
}
