<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class SyncProducts extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $listeners = ['syncModal'];

    public $type;
    
    public $store_url;

    public $syncModal = false;

    public function updatedType() : void
    {
        if ($this->type === 'woocommerce') {
            $this->store_url = settings()->woocommerce_store_url;
        } elseif ($type === 'shopify') {
            $this->store_url = settings()->shopify_store_url;
        }
    }


    public function sync() : void
    {
        // Connect to the user's e-commerce store
        if ($this->type === 'woocommerce') {
            $client = new \Automattic\WooCommerce\Client(
                settings()->woocommerce_store_url,
                settings()->woocommerce_api_key,
                settings()->woocommerce_api_secret,
                ['wp_api' => true, 'version' => 'wc/v3']
            );
        } elseif ($type === 'shopify') {
            $client = new \Shopify\Client([
                'shop_domain' => settings()->shopify_store_url,
                'api_key' => settings()->shopify_api_key,
                'api_secret' => settings()->shopify_api_secret,
            ]);
        }

        // Retrieve the products from the user's e-commerce store
        $products = $client->get('products');

        // Compare the products in the user's e-commerce store with the products in your app
        $missing_products = array_diff($products, $app_products);

        // Insert any missing products into your app
        foreach ($missing_products as $product) {
            $app_product = new Product();
            $app_product->name = $product->name;
            $app_product->price = $product->price;
            // Map other fields as needed
            $app_product->save();
        }
    }

    public function render(): View|Factory
    {
        return view('livewire.products.sync-products');
    }
}
