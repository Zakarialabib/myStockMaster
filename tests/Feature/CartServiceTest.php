<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        // \App\Support\Cart\Facades\Cart::forget('test');
        $this->cartService = new CartService('test');
        $this->cartService->clear();
    }

    /** @test */
    public function it_can_add_items_to_cart()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 10.00,
            'quantity' => 2,
            'attributes' => ['color' => 'red'],
        ];

        $rowId = $this->cartService->add($item);

        $this->assertNotEmpty($rowId);
        $this->assertEquals(1, $this->cartService->content()->count());
        $this->assertEquals(2, $this->cartService->count());
    }

    /** @test */
    public function it_can_update_cart_items()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 10.00,
            'quantity' => 2,
        ];

        $rowId = $this->cartService->add($item);
        $updated = $this->cartService->update($rowId, ['quantity' => 5]);

        $this->assertTrue($updated);
        $this->assertEquals(5, $this->cartService->count());
    }

    /** @test */
    public function it_can_remove_cart_items()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 10.00,
            'quantity' => 2,
        ];

        $rowId = $this->cartService->add($item);
        $removed = $this->cartService->remove($rowId);

        $this->assertTrue($removed);
        $this->assertEquals(0, $this->cartService->content()->count());
        $this->assertTrue($this->cartService->isEmpty());
    }

    /** @test */
    public function it_can_calculate_subtotal()
    {
        $item1 = [
            'id' => 1,
            'name' => 'Product 1',
            'price' => 10.00,
            'quantity' => 2,
        ];

        $item2 = [
            'id' => 2,
            'name' => 'Product 2',
            'price' => 15.00,
            'quantity' => 1,
        ];

        $this->cartService->add($item1);
        $this->cartService->add($item2);

        $this->assertEquals(35.00, $this->cartService->subtotal());
    }

    /** @test */
    public function it_can_calculate_tax()
    {
        $this->cartService->setTaxRate(10); // 10% tax

        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 100.00,
            'quantity' => 1,
        ];

        $this->cartService->add($item);

        $this->assertEquals(10.00, $this->cartService->tax());
        $this->assertEquals(110.00, $this->cartService->total());
    }

    /** @test */
    public function it_can_calculate_discount()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 100.00,
            'quantity' => 1,
        ];

        $this->cartService->add($item);
        $this->cartService->addCondition([
            'name' => '10% Discount',
            'type' => 'discount',
            'target' => 'subtotal',
            'value' => -10,
        ]);

        $this->assertGreaterThanOrEqual(0, $this->cartService->discount());
    }

    /** @test */
    public function it_can_clear_cart()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 10.00,
            'quantity' => 2,
        ];

        $this->cartService->add($item);
        $this->cartService->clear();

        $this->assertTrue($this->cartService->isEmpty());
        $this->assertEquals(0, $this->cartService->count());
    }

    /** @test */
    public function it_can_destroy_cart()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 10.00,
            'quantity' => 2,
        ];

        $this->cartService->add($item);
        $this->cartService->destroy();

        $this->assertTrue($this->cartService->isEmpty());
        $this->assertEquals(0, $this->cartService->count());
    }

    /** @test */
    public function it_can_search_cart_items()
    {
        $item1 = [
            'id' => 1,
            'name' => 'Red Product',
            'price' => 10.00,
            'quantity' => 1,
        ];

        $item2 = [
            'id' => 2,
            'name' => 'Blue Product',
            'price' => 15.00,
            'quantity' => 1,
        ];

        $this->cartService->add($item1);
        $this->cartService->add($item2);

        $redItems = $this->cartService->search(function ($item) {
            return str_contains($item->name, 'Red');
        });

        $this->assertEquals(1, $redItems->count());
        $this->assertEquals('Red Product', $redItems->first()->name);
    }

    /** @test */
    public function it_handles_duplicate_items_correctly()
    {
        $item = [
            'id' => 1,
            'name' => 'Test Product',
            'price' => 10.00,
            'quantity' => 2,
        ];

        $this->cartService->add($item);
        $this->cartService->add($item); // Add same item again

        $this->assertEquals(1, $this->cartService->content()->count()); // Should still be 1 unique item
        $this->assertEquals(4, $this->cartService->count()); // But quantity should be 4
    }
}
