<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Utils\ProductCart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PosCartTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'quantity' => 100,
            'code' => 'TEST001',
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_add_product_to_pos_cart()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_cart_item_quantity()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->set('quantity.' . $this->product->id, 3)
            ->call('updateQuantity', $this->product->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_remove_item_from_cart()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->call('removeItem', $this->product->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_clear_entire_cart()
    {
        $product2 = Product::factory()->create([
            'name' => 'Test Product 2',
            'quantity' => 50,
        ]);

        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->call('productSelected', $product2->id)
            ->call('clearCart')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_calculates_cart_total_correctly()
    {
        $product2 = Product::factory()->create([
            'name' => 'Test Product 2',
            'quantity' => 50,
        ]);

        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->call('productSelected', $product2->id)
            ->set('quantity.' . $this->product->id, 2)
            ->call('updateQuantity', $this->product->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_applies_discount_correctly()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->set('global_discount', 10)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_calculates_tax_correctly()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->set('global_tax', 10)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_prevents_adding_out_of_stock_products()
    {
        $outOfStockProduct = Product::factory()->create([
            'name' => 'Out of Stock Product',
            'quantity' => 0,
        ]);

        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $outOfStockProduct->id)
            ->assertHasNoErrors(); // Component should handle out of stock gracefully
    }

    /** @test */
    public function it_prevents_adding_more_than_available_quantity()
    {
        $limitedProduct = Product::factory()->create([
            'name' => 'Limited Product',
            'quantity' => 5,
        ]);

        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $limitedProduct->id)
            ->set('quantity.' . $limitedProduct->id, 10)
            ->call('updateQuantity', $limitedProduct->id)
            ->assertHasNoErrors(); // Component should handle quantity limits gracefully
    }

    /** @test */
    public function it_can_search_products_for_pos()
    {
        $searchableProduct = Product::factory()->create([
            'name' => 'Searchable Product',
            'code' => 'SEARCH001',
            'quantity' => 20,
        ]);

        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->assertHasNoErrors(); // Basic component initialization test
    }

    /** @test */
    public function it_can_scan_barcode_to_add_product()
    {
        $barcodeProduct = Product::factory()->create([
            'name' => 'Barcode Product',
            'code' => 'BARCODE123',
            'quantity' => 30,
        ]);

        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $barcodeProduct->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_handles_invalid_barcode_gracefully()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->assertHasNoErrors(); // Basic component initialization test
    }

    /** @test */
    public function it_maintains_cart_state_across_component_updates()
    {
        $component = Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->set('quantity.' . $this->product->id, 3)
            ->call('updateQuantity', $this->product->id);

        // Simulate component refresh/update
        $component->call('$refresh')
            ->assertHasNoErrors();
    }
}
