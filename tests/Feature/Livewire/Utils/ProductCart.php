<?php

declare(strict_types=1);

use App\Livewire\Utils\ProductCart;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->warehouse = Warehouse::factory()->create();
    $this->product = Product::factory()->create([
        'name' => 'Cart Product',
        'code' => 'CART001',
        'price' => 20.00,
        'cost' => 10.00,
        'tax_type' => 0,
        'tax_amount' => 0,
    ]);

    // Attach the product to the test warehouse so productSelected can resolve its stock/price.
    ProductWarehouse::query()->updateOrCreate(
        ['product_id' => $this->product->id, 'warehouse_id' => $this->warehouse->id],
        [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'qty' => 10,
            'cost' => 10.00,
            'price' => 20.00,
            'stock_alert' => 10,
        ],
    );
});

it('adds a product to the cart via productSelected', function (): void {
    Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ])
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->assertHasNoErrors()
        ->assertSet('quantity', function (array $quantity): bool {
            return ($quantity[$this->product->id] ?? 0) === 1;
        });
});

it('alerts and does not duplicate an already-added product', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $component->call('productSelected', $this->product->id, $this->warehouse->id);

    expect($component->instance()->cartCount())->toBe(1);
});

it('removes an item from the cart via removeItem', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $rowId = $component->instance()->cartContent()->first()->rowId;

    $component->call('removeItem', $rowId)
        ->assertHasNoErrors();

    expect($component->instance()->cartCount())->toBe(0);
});

it('updates the price of a cart item via updatePrice', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $rowId = $component->instance()->cartContent()->first()->rowId;

    // New price set on the component, then pushed into the cart item.
    $component->set('price', [$this->product->id => 25.00])
        ->call('updatePrice', $this->product->id, $rowId)
        ->assertHasNoErrors();

    $item = $component->instance()->cartContent()->first();
    expect((float) $item->price)->toBe(25.00);
});

it('updates the quantity of a cart item via updateQuantity', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $rowId = $component->instance()->cartContent()->first()->rowId;

    $component->set('quantity', [$this->product->id => 3])
        ->call('updateQuantity', $rowId, $this->product->id)
        ->assertHasNoErrors();

    expect($component->instance()->cartCount())->toBe(3);
});

it('opens the discount modal via discountModal', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $rowId = $component->instance()->cartContent()->first()->rowId;

    $component->call('discountModal', $this->product->id, $rowId)
        ->assertSet('discountModal', true);
});

it('applies a fixed product discount via productDiscount', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $rowId = $component->instance()->cartContent()->first()->rowId;

    // Open the discount modal (pre-fills discount_type/item_discount from the cart),
    // then the user edits the discount value/type in the modal inputs. Set the type
    // first (its updated hook resets the amount), then the amount last so it sticks.
    $component->call('discountModal', $this->product->id, $rowId)
        ->set('discount_type.' . $this->product->id, 'fixed')
        ->set('item_discount.' . $this->product->id, 5.00)
        ->call('productDiscount', $rowId, $this->product->id)
        ->assertHasNoErrors();

    $item = $component->instance()->cartContent()->first();
    // price (20) reduced by the fixed discount (5): 20 + 0 (existing) - 5 = 15
    expect((float) $item->price)->toBe(15.00);
});

it('applies a percentage product discount via productDiscount', function (): void {
    $component = Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ]);

    $component->call('productSelected', $this->product->id, $this->warehouse->id);
    $rowId = $component->instance()->cartContent()->first()->rowId;

    // Open the discount modal (pre-fills from the cart), then edit the value/type.
    // Set the type first (its updated hook resets the amount), then the amount last.
    $component->call('discountModal', $this->product->id, $rowId)
        ->set('discount_type.' . $this->product->id, 'percentage')
        ->set('item_discount.' . $this->product->id, 10)
        ->call('productDiscount', $rowId, $this->product->id)
        ->assertHasNoErrors();

    $item = $component->instance()->cartContent()->first();
    // 10% of (price 20 + existing discount 0) = 2 => 20 - 2 = 18
    expect((float) $item->price)->toBe(18.00);
});

it('reacts to warehouse selection via updatedWarehouseId', function (): void {
    // A second warehouse with an explicit id (avoids sqlite autoincrement collision
    // when two warehouses share a sequence in one test).
    $otherWarehouse = Warehouse::factory()->create(['id' => 991]);

    // updatedWarehouseId is a lifecycle hook, so trigger it by changing the bound property.
    Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ])
        ->set('warehouse_id', $otherWarehouse->id)
        ->assertSet('warehouse_id', $otherWarehouse->id);
});

it('applies global tax/discount/shipping setters without error', function (): void {
    // updated* hooks fire automatically when their bound properties change.
    Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'warehouseId' => $this->warehouse->id,
    ])
        ->set('global_tax', 5)
        ->set('global_discount', 10)
        ->set('shipping_amount', 15)
        ->assertHasNoErrors();
});

it('mounts with an existing record data payload', function (): void {
    // A sale/purchase record-like payload (only the fields ProductCart reads are needed).
    $data = [
        'discount_percentage' => 5,
        'tax_percentage' => 2,
        'shipping_amount' => 12,
        'warehouse_id' => $this->warehouse->id,
    ];

    Livewire::test(ProductCart::class, [
        'cartInstance' => 'pos',
        'data' => $data,
    ])
        ->assertSet('warehouse_id', $this->warehouse->id)
        ->assertSet('shipping_amount', 12);
});
