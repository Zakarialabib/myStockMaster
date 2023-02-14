<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ApiToken extends Component
{
    public $token;

    public $custom_store_url;

    public $authenticated = false;

    public function mount()
    {
        $this->custom_store_url = settings()->custom_store_url;
       $this->product_count = settings()->custom_products;
    }

    public function createToken()
    {
        $this->token = auth()->user()->createToken('api-token')->plainTextToken;
    }

    public function deleteToken()
    {
        auth()->user()->tokens()->delete();

        $this->token = null;
        $this->authenticated = false;
    }

    public function authenticate()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get($this->custom_store_url . '/api/authenticate');

        if ($response->status() === 200) {

            $this->authenticated = true;
            
            settings()->update([
                'custom_store_url' => $this->custom_store_url,
                'custom_api_key' => $this->token,
                'custom_api_secret' => 'your-secret-value', // replace with your own secret value
                'custom_last_sync' => null, // set to null initially
                'custom_products' => null // set to null initially
            ]);

            // update the custom_last_sync and custom_products values
            $response = Http::get('127.0.0.1:8000/api/products');

            $productCount = $response->json()['meta']['product_count'];

            if ($response->status() === 200) {
                settings()->update([
                    'custom_last_sync' => now()->toDateTimeString(),
                    'custom_products' => $productCount
                ]);
            }

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
