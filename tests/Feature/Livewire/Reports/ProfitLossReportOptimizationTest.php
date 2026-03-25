<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Reports;

use App\Livewire\Reports\ProfitLossReport;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ProfitLossReportOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_count_for_calculate_profit()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $warehouse1 = Warehouse::create([
            'name'    => 'Warehouse 1',
            'city'    => 'City 1',
            'phone'   => '1234567890',
            'email'   => 'w1@example.com',
            'country' => 'Country 1',
        ]);
        $warehouse2 = Warehouse::create([
            'name'    => 'Warehouse 2',
            'city'    => 'City 2',
            'phone'   => '0987654321',
            'email'   => 'w2@example.com',
            'country' => 'Country 2',
        ]);

        $customer = \App\Models\Customer::create([
            'name'    => 'John Doe',
            'email'   => 'john@example.com',
            'phone'   => '1234567890',
            'city'    => 'City',
            'country' => 'Country',
            'address' => 'Address',
        ]);

        // Create 10 sales, each with 5 sale details.
        for ($i = 0; $i < 10; $i++) {
            $sale = Sale::create([
                'date'                => now()->subDays(5)->format('Y-m-d'),
                'customer_id'         => $customer->id,
                'user_id'             => $user->id,
                'warehouse_id'        => $warehouse1->id,
                'tax_percentage'      => 0,
                'tax_amount'          => 0,
                'discount_percentage' => 0,
                'discount_amount'     => 0,
                'shipping_amount'     => 0,
                'total_amount'        => 50000,
                'paid_amount'         => 50000,
                'due_amount'          => 0,
                'status'              => \App\Enums\SaleStatus::COMPLETED->value,
                'payment_status'      => 'paid',
                'payment_id'          => null,
                'shipping_status'     => 'delivered',
            ]);

            for ($j = 0; $j < 5; $j++) {
                $product = Product::factory()->create();

                // create ProductWarehouse
                $pw = new ProductWarehouse();
                $pw->product_id = $product->id;
                $pw->warehouse_id = $warehouse1->id;
                $pw->cost = 50;
                $pw->price = 100;
                $pw->qty = 10;
                $pw->stock_alert = 2;
                $pw->save();

                SaleDetails::create([
                    'sale_id'                 => $sale->id,
                    'product_id'              => $product->id,
                    'warehouse_id'            => $warehouse1->id,
                    'name'                    => $product->name,
                    'code'                    => $product->code,
                    'quantity'                => 2,
                    'price'                   => 10000,
                    'unit_price'              => 10000,
                    'sub_total'               => 20000,
                    'product_discount_amount' => 0,
                    'product_tax_amount'      => 0,
                    'product_discount_type'   => 'fixed',
                ]);
            }
        }

        DB::enableQueryLog();

        $component = Livewire::test(ProfitLossReport::class);
        $component->call('setValues');

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Guard against N+1 regressions in the future (the baseline was 685)
        $this->assertLessThan(100, $queryCount, "Total Queries Executed: {$queryCount}");

        // Print the query count so we can see it in output
        echo "\nTOTAL QUERIES: ".$queryCount."\n";
    }
}
