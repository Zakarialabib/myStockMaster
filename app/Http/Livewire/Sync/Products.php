<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sync;

use App\Enums\IntegrationType;
use App\Jobs\SyncCustomProducts;
use App\Models\Product;
use App\Models\Integration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class Products extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    /** @var array<string> */
    public $listeners = ['syncModal'];

    public $type;

    public $syncModal = false;

    protected $rules = [
        'type' => 'required',
    ];

    public function syncModal(): void
    {
        $this->syncModal = true;
    }

    public function recieveData()
    {
        $integration = Integration::where('type', $this->type)->first();
        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$integration->api_key,
        ]);

        if ($this->type === IntegrationType::WOOCOMMERCE) {
            $response = new \Automattic\WooCommerce\Client(
                $integration->store_url,
                $integration->api_key,
                $integration->api_secret,
                ['wp_api' => true, 'version' => 'wc/v3']
            );
        } elseif ($this->type === IntegrationType::SHOPIFY) {
            $response = new \Shopify\Client([
                'shop_domain' => $integration->store_url,
                'api_key'     => $integration->api_key,
                'api_secret'  => $integration->api_secret,
            ]);
        } elseif ($this->type === IntegrationType::YOUCAN) {
            $response = $client->get($integration->store_url.'/products');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $data = $response->json()['data'];
                SyncYoucanProducts::dispatch($data);
                $this->alert('success', 'Sync from youcan to inventory completed');
                $this->syncModal = false;
            }
        } elseif ($this->type === IntegrationType::CUSTOM) {
            $response = $client->get($integration->store_url.'/api/products');

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $data = $response->json()['data'];
                $batch = Bus::batch([
                    new SyncCustomProducts($data),
                ])->then(function (Batch $batch) {
                    $this->alert('success', 'Sync from ecommerce to inventory completed'.$batch->finished());
                })->catch(function (Batch $batch, Throwable $e) {
                    $this->alert('success', 'Sync Failed'.$e->getMessage());
                })->name('sync Custom Products')->dispatch();

                $this->syncModal = false;
            }
        }
    }

    public function sendData()
    {
        $integration = Integration::where('type', $this->type)->first();

        if ($this->type === IntegrationType::WOOCOMMERCE) {
            $response = new \Automattic\WooCommerce\Client(
                $integration->store_url,
                $integration->api_key,
                $integration->api_secret,
                ['wp_api' => true, 'version' => 'wc/v3']
            );
        } elseif ($this->type === IntegrationType::SHOPIFY) {
            $response = new \Shopify\Client([
                'shop_domain' => $integration->store_url,
                'api_key'     => $integration->api_key,
                'api_secret'  => $integration->api_secret,
            ]);
        } elseif ($this->type === IntegrationType::YOUCAN) {
            $client = Http::withHeaders([
                'Authorization' => 'Bearer '.$integration->api_key,
            ]);
            $response = $client->get($integration->store_url.'/api/products');
        } elseif ($this->type === IntegrationType::CUSTOM) {
            $client = Http::withHeaders([
                'Authorization' => 'Bearer '.$integration->api_key,
            ]);

            $response = $client->get($integration->store_url.'/api/products');
            $inventoryProducts = Product::with('category')->get();

            $ecomProducts = $response->json()['data'];

            $data = [];
            // Check which products need to be created
            foreach ($inventoryProducts as $product) {
                if ( ! in_array($product->code, array_column($ecomProducts, 'code'))) {
                    $data[] = [
                        'name'       => $product['name'],
                        'code'       => $product['code'],
                        'price'      => $product['price'],
                        'quantity'   => $product['quantity'],
                        'categoryId' => $product['category']->name,
                    ];
                }
            }

            $client->post($integration->store_url.'/api/products/bulk', ['data' => $data]);

            Log::info(count($data).' new products created in e-commerce store.');

            return response()->json(['message' => count($data).' new products created in e-commerce store.']);
        }
    }

    public function render()
    {
        return view('livewire.sync.products');
    }
}
