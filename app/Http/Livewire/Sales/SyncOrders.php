<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;

class SyncOrders extends Component
{
    public function syncOrder($order_id)
    {
        // configure the WooCommerce REST API client
        $woocommerce = new Client(
            'https://your-store.com',
            'ck_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            'cs_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            ['wp_api' => true, 'version' => 'wc/v3']
        );

        // fetch the order data from WooCommerce
        $order = $woocommerce->get('orders/'.$order_id);

        // map the data to the Laravel Sale model
        $sale = new Sale();
        $sale->fill([
            'id'   => $order['id'],
            'date' => $order['date'],
            // map the remaining fields
        ]);

        // save the Sale model to the database
        return $sale->save();
    }

    public function render()
    {
        return view('livewire.sales.sync-orders');
    }
}
