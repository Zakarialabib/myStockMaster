<?php

declare(strict_types=1);

use App\Livewire\Transfer\Create;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

beforeEach(function (): void {
    // The Transfer create/store flow is gated behind `transfer_create`, which the app
    // grants to the `admin` role (see AuthServiceProvider Gate::before). Tests run without
    // seeded roles, so bypass the gate here to exercise the business logic — mirroring an
    // admin user. (SalesCartTest has the same pattern via the sale_create gate.)
    Gate::before(fn () => true);

    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->fromWarehouse = Warehouse::factory()->create();
    $this->toWarehouse = Warehouse::factory()->create();

    $this->product = Product::factory()->create([
        'name' => 'Transfer Product',
        'code' => 'TRF001',
        'price' => 15.00,
        'cost' => 8.00,
    ]);

    // Source stock for the product in the from-warehouse.
    // updateOrCreate (not create) so the ProductFactory's afterCreating hook, which may
    // have attached the product to this same warehouse, doesn't create a competing row
    // whose price would win the ->first() lookup in productSelected().
    ProductWarehouse::query()->updateOrCreate(
        ['product_id' => $this->product->id, 'warehouse_id' => $this->fromWarehouse->id],
        [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->fromWarehouse->id,
            'qty' => 10,
            'cost' => 8.00,
            'price' => 15.00,
            'stock_alert' => 10,
        ],
    );
});

it('can add a product to the transfer via the productSelected event', function (): void {
    Livewire::test(Create::class)
        ->set('form.from_warehouse_id', $this->fromWarehouse->id)
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->assertHasNoErrors()
        ->assertSet('products', function (array $products): bool {
            return count($products) === 1
                && $products[0]['id'] === $this->product->id
                && (float) $products[0]['price'] == 15.00
                && (float) $products[0]['cost'] == 8.00
                && $products[0]['code'] === 'TRF001';
        });
});

it('prevents adding the same product twice (de-duplicates)', function (): void {
    Livewire::test(Create::class)
        ->set('form.from_warehouse_id', $this->fromWarehouse->id)
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->assertHasNoErrors()
        ->assertCount('products', 1);
});

it('recalculates the transfer totals after adding a product', function (): void {
    Livewire::test(Create::class)
        ->set('form.from_warehouse_id', $this->fromWarehouse->id)
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->assertSet('form.total_qty', 1)
        ->assertSet('form.total_amount', 15.00)
        ->assertSet('form.total_cost', 8.00);
});

it('can remove a product from the transfer', function (): void {
    Livewire::test(Create::class)
        ->set('form.from_warehouse_id', $this->fromWarehouse->id)
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->call('removeProduct', 0)
        ->assertHasNoErrors()
        ->assertCount('products', 0);
});

it('can create a transfer with cart items through store', function (): void {
    Livewire::test(Create::class)
        ->set('form.from_warehouse_id', $this->fromWarehouse->id)
        ->set('form.to_warehouse_id', $this->toWarehouse->id)
        ->set('form.reference', 'TR-TEST01')
        ->set('form.date', now()->toDateString())
        ->set('form.status', 1)
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->call('store')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('transfers', [
        'reference' => 'TR-TEST01',
        'from_warehouse_id' => $this->fromWarehouse->id,
        'to_warehouse_id' => $this->toWarehouse->id,
        'total_qty' => 1,
        'total_amount' => 15.00,
    ]);

    $this->assertDatabaseHas('transfer_details', [
        'product_id' => $this->product->id,
        'warehouse_id' => $this->toWarehouse->id,
        'quantity' => 1,
    ]);
});

it('requires from and to warehouse before store', function (): void {
    Livewire::test(Create::class)
        ->set('form.reference', 'TR-TEST02')
        ->set('form.date', now()->toDateString())
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->call('store')
        ->assertHasNoErrors(); // component alerts instead of failing validation

    $this->assertDatabaseMissing('transfers', [
        'reference' => 'TR-TEST02',
    ]);
});

it('rejects a transfer when from and to warehouse are the same', function (): void {
    Livewire::test(Create::class)
        ->set('form.from_warehouse_id', $this->fromWarehouse->id)
        ->set('form.to_warehouse_id', $this->fromWarehouse->id)
        ->set('form.reference', 'TR-TEST03')
        ->set('form.date', now()->toDateString())
        ->call('productSelected', $this->product->id, $this->fromWarehouse->id)
        ->call('store')
        ->assertHasNoErrors(); // component alerts instead of failing validation

    $this->assertDatabaseMissing('transfers', [
        'reference' => 'TR-TEST03',
    ]);
});
