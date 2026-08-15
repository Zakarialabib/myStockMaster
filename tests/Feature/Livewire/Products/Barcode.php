<?php

declare(strict_types=1);

use App\Actions\Products\GenerateBarcodesAction;
use App\Livewire\Products\Barcode;
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
        'name' => 'Barcode Product',
        'code' => 'BARCODE001',
        'price' => 12.50,
    ]);

    // Attach the product to the test warehouse so productSelected can resolve it.
    // updateOrCreate (not create) so the ProductFactory's afterCreating hook, which may
    // have attached the product to this same warehouse, doesn't create a competing row
    // whose price would win the ->first() lookup in productSelected().
    ProductWarehouse::query()->updateOrCreate(
        ['product_id' => $this->product->id, 'warehouse_id' => $this->warehouse->id],
        [
            'product_id' => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'qty' => 20,
            'cost' => 5.00,
            'price' => 12.50,
            'stock_alert' => 10,
        ],
    );
});

it('adds a product to the barcode list via the productSelected event', function (): void {
    Livewire::test(Barcode::class)
        ->set('warehouse_id', $this->warehouse->id)
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->assertHasNoErrors()
        ->assertSet('products', function (array $products): bool {
            return count($products) === 1
                && $products[0]['id'] === $this->product->id
                && $products[0]['code'] === 'BARCODE001'
                && (float) $products[0]['price'] == 12.50
                && $products[0]['barcode_symbology'] === $this->product->barcode_symbology;
        });
});

it('allows adding the same product multiple times (no de-duplication)', function (): void {
    Livewire::test(Barcode::class)
        ->set('warehouse_id', $this->warehouse->id)
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->assertHasNoErrors()
        ->assertCount('products', 2);
});

it('removes a product from the barcode list', function (): void {
    Livewire::test(Barcode::class)
        ->set('warehouse_id', $this->warehouse->id)
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->call('deleteProduct', $this->product->id)
        ->assertHasNoErrors()
        ->assertCount('products', 0);
});

it('falls back to the product price when no warehouse price exists', function (): void {
    $otherWarehouse = Warehouse::factory()->create();

    Livewire::test(Barcode::class)
        ->set('warehouse_id', $otherWarehouse->id)
        ->call('productSelected', $this->product->id, $otherWarehouse->id)
        ->assertHasNoErrors()
        ->assertSet('products', function (array $products): bool {
            return count($products) === 1
                && (float) $products[0]['price'] == 12.50; // product.price fallback
        });
});

it('generates barcodes for the selected products', function (): void {
    // The real GenerateBarcodesAction depends on the (environment-removed) milon/barcode
    // package, so bind a stub that returns one barcode entry per product — mirroring the
    // real action's shape (name, code, barcode SVG, price) — to verify the component
    // wiring (collect products -> invoke action -> store generated barcodes -> render).
    app()->bind(GenerateBarcodesAction::class, static function (): \Closure {
        return static function (array $products): array {
            return array_map(static fn (array $product): array => [
                'code' => $product['code'],
                'name' => $product['name'],
                'barcode' => '<svg>' . $product['code'] . '</svg>',
                'price' => $product['price'],
            ], $products);
        };
    });

    Livewire::test(Barcode::class)
        ->set('warehouse_id', $this->warehouse->id)
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->call('generateBarcodes')
        ->assertHasNoErrors()
        ->assertSet('barcodes', function (array $barcodes): bool {
            return count($barcodes) === 1
                && $barcodes[0]['code'] === 'BARCODE001'
                && $barcodes[0]['name'] === 'Barcode Product'
                && str_contains($barcodes[0]['barcode'], 'BARCODE001');
        });
});

it('builds a barcode entry with the product symbology', function (): void {
    Livewire::test(Barcode::class)
        ->set('warehouse_id', $this->warehouse->id)
        ->call('productSelected', $this->product->id, $this->warehouse->id)
        ->assertSet('products', function (array $products): bool {
            return $products[0]['barcode_symbology'] === $this->product->barcode_symbology
                && $products[0]['quantity'] === 1;
        });
});
