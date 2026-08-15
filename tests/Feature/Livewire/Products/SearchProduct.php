<?php

declare(strict_types=1);

use App\Livewire\Products\SearchProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->warehouse = Warehouse::factory()->create();
    $this->category = Category::factory()->create(['name' => 'Beverages']);

    $this->product = Product::factory()->create([
        'name' => 'Search Product',
        'code' => 'SEARCH001',
        'category_id' => $this->category->id,
        'price' => 9.00,
    ]);
});

it('dispatches productSelected when a warehouse is selected', function (): void {
    Livewire::test(SearchProduct::class, ['warehouseId' => $this->warehouse->id])
        ->call('selectProduct', $this->product->id)
        ->assertDispatched('productSelected', productId: $this->product->id, warehouseId: $this->warehouse->id);
});

it('alerts instead of dispatching when no warehouse is selected', function (): void {
    Livewire::test(SearchProduct::class)
        ->call('selectProduct', $this->product->id)
        ->assertNotDispatched('productSelected');
});

it('handles a barcode scan for an existing product', function (): void {
    Livewire::test(SearchProduct::class, ['warehouseId' => $this->warehouse->id])
        ->call('handleBarcodeScan', 'SEARCH001')
        ->assertDispatched('barcode-scanned-success')
        ->assertDispatched('productSelected', productId: $this->product->id, warehouseId: $this->warehouse->id)
        ->assertSet('querySearch', '');
});

it('handles a barcode scan for an unknown product', function (): void {
    Livewire::test(SearchProduct::class)
        ->call('handleBarcodeScan', 'DOESNOTEXIST')
        ->assertDispatched('barcode-scanned-error')
        ->assertSet('querySearch', 'DOESNOTEXIST')
        ->assertNotDispatched('productSelected');
});

it('loads more products by increasing the page size', function (): void {
    Livewire::test(SearchProduct::class)
        ->assertSet('showCount', 9)
        ->call('loadMore')
        ->assertSet('showCount', 14);
});

it('reacts to warehouse selection via updatedWarehouseId', function (): void {
    $other = Warehouse::factory()->create(['id' => 992]);

    // updatedWarehouseId is an #[On('warehouseSelected')] listener, so fire the event.
    Livewire::test(SearchProduct::class)
        ->dispatch('warehouseSelected', $other->id)
        ->assertSet('warehouse_id', $other->id);
});

it('exposes active categories via the categories computed property', function (): void {
    $component = Livewire::test(SearchProduct::class);

    $categories = $component->instance()->categories;

    expect($categories)->toHaveKey($this->category->id)
        ->and($categories[$this->category->id])->toBe('Beverages');
});

it('resets the search query via resetQuery', function (): void {
    Livewire::test(SearchProduct::class)
        ->set('querySearch', 'something')
        ->call('resetQuery')
        ->assertSet('querySearch', '');
});

it('filters products by the search query on render', function (): void {
    // A second product that should NOT match the query.
    Product::factory()->create(['name' => 'Other Widget', 'code' => 'OTHER001']);

    $component = Livewire::test(SearchProduct::class)
        ->set('querySearch', 'Search Product');

    $ids = $component->instance()->render()->getData()['products']->pluck('id')->all();

    expect($ids)->toContain($this->product->id)
        ->and($ids)->toContain($this->product->id)
        ->and($ids)->not->toContain(\App\Models\Product::where('code', 'OTHER001')->first()->id);
});

it('filters products by category on render', function (): void {
    $otherCategory = Category::factory()->create();
    $otherProduct = Product::factory()->create(['category_id' => $otherCategory->id]);

    $component = Livewire::test(SearchProduct::class)
        ->set('category_id', $this->category->id);

    $ids = $component->instance()->render()->getData()['products']->pluck('id')->all();

    expect($ids)->toContain($this->product->id)
        ->and($ids)->not->toContain($otherProduct->id);
});
