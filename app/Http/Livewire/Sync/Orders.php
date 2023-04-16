<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sync;

use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Orders extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var array<string> */
    public $listeners = ['syncModal'];

    public $type;

    public $store_url;

    public $syncModal = false;

    public function updatedType(): void
    {
        if ($this->type === 'woocommerce') {
            $this->store_url = settings()->woocommerce_store_url;
        } elseif ($this->type === 'shopify') {
            $this->store_url = settings()->shopify_store_url;
        } elseif ($this->type === 'custom') {
            $this->store_url = settings()->custom_store_url;
        }
    }

    public function sync()
    {
        // Connect to the user's e-commerce store
        if ($this->type === 'woocommerce') {
            $client = new \Automattic\WooCommerce\Client(
                settings()->woocommerce_store_url,
                settings()->woocommerce_api_key,
                settings()->woocommerce_api_secret,
                ['wp_api' => true, 'version' => 'wc/v3']
            );
        } elseif ($this->type === 'shopify') {
            $client = new \Shopify\Client([
                'shop_domain' => settings()->shopify_store_url,
                'api_key'     => settings()->shopify_api_key,
                'api_secret'  => settings()->shopify_api_secret,
            ]);
        } elseif ($this->type === 'custom') {
            $client = Http::withHeaders([
                'Authorization' => 'Bearer '.settings()->custom_api_key,
            ])->get(settings()->custom_store_url.'/api');
        }

        // Get the orders from the e-commerce store
        $ecomOrders = $client->get('/orders');

        // Iterate over each e-commerce order and sync it to the inventory system
        foreach ($ecomOrders as $order) {
            // Check if the order already exists in the inventory system
            $existingOrder = Sale::where('reference', $order['reference'])->first();

            if (empty($existingOrder)) {
                // Create a new order in the inventory system
                $newOrder = new Sale();
                $newOrder->order_number = $order['reference'];
                $newOrder->total = $order['total'];
                // Map other fields as needed
                $newOrder->save();
            }
        }
    }

    public function render()
    {
        return view('livewire.sync.orders');
    }
}
