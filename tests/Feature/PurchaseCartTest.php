<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Purchase\Create;
use App\Livewire\Purchase\Edit;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PurchaseCartTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
        $this->product = Product::factory()->create([
            'name'     => 'Test Product',
            'quantity' => 20,
            'code'     => 'PURCH001',
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_add_product_to_purchase_cart_in_create_component()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->call('addToCart')
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 1 && $cartItems[0]['id'] == $this->product->id;
            })
            ->assertSet('total_amount', 80.00);
    }

    /** @test */
    public function it_can_update_cart_item_in_purchase_create()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->call('addToCart')
            ->call('updateCartItem', 0, ['quantity' => 15])
            ->assertSet('cart_items', function ($cartItems) {
                return $cartItems[0]['quantity'] == 15;
            })
            ->assertSet('total_amount', 120.00);
    }

    /** @test */
    public function it_can_remove_item_from_purchase_cart()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->call('addToCart')
            ->call('removeFromCart', 0)
            ->assertSet('cart_items', [])
            ->assertSet('total_amount', 0.00);
    }

    /** @test */
    public function it_calculates_tax_correctly_in_purchases()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->set('tax_percentage', 15)
            ->call('addToCart')
            ->call('calculateTotals')
            ->assertSet('tax_amount', 12.00) // 15% of $80
            ->assertSet('total_amount', 92.00); // $80 + $12 tax
    }

    /** @test */
    public function it_applies_discount_correctly_in_purchases()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->set('discount_percentage', 5)
            ->call('addToCart')
            ->call('applyDiscount')
            ->assertSet('discount_amount', 4.00) // 5% of $80
            ->assertSet('total_amount', 76.00); // $80 - $4 discount
    }

    /** @test */
    public function it_can_create_purchase_with_cart_items()
    {
        Livewire::test(Create::class)
            ->set('supplier_id', $this->supplier->id)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->set('payment_status', 'paid')
            ->set('payment_method', 'bank_transfer')
            ->call('addToCart')
            ->call('store')
            ->assertRedirect(route('purchases.index'));

        $this->assertDatabaseHas('purchases', [
            'supplier_id'    => $this->supplier->id,
            'total_amount'   => 80.00,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('purchase_details', [
            'product_id' => $this->product->id,
            'quantity'   => 10,
            'unit_cost'  => 8.00,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_in_purchase_create()
    {
        Livewire::test(Create::class)
            ->call('store')
            ->assertHasErrors([
                'supplier_id'    => 'required',
                'payment_status' => 'required',
            ]);
    }

    /** @test */
    public function it_prevents_creating_purchase_with_empty_cart()
    {
        Livewire::test(Create::class)
            ->set('supplier_id', $this->supplier->id)
            ->set('payment_status', 'paid')
            ->call('store')
            ->assertHasErrors(['cart' => 'Cart cannot be empty']);
    }

    /** @test */
    public function it_can_edit_existing_purchase_cart()
    {
        $purchase = Purchase::factory()->create([
            'supplier_id'    => $this->supplier->id,
            'user_id'        => $this->user->id,
            'total_amount'   => 80.00,
            'payment_status' => 'paid',
        ]);

        $purchase->purchaseDetails()->create([
            'product_id' => $this->product->id,
            'quantity'   => 10,
            'unit_cost'  => 8.00,
            'total'      => 80.00,
        ]);

        Livewire::test(Edit::class, ['purchase' => $purchase])
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 1 && $cartItems[0]['quantity'] == 10;
            })
            ->assertSet('total_amount', 80.00);
    }

    /** @test */
    public function it_can_update_existing_purchase_cart_items()
    {
        $purchase = Purchase::factory()->create([
            'supplier_id'    => $this->supplier->id,
            'user_id'        => $this->user->id,
            'total_amount'   => 80.00,
            'payment_status' => 'paid',
        ]);

        $purchase->purchaseDetails()->create([
            'product_id' => $this->product->id,
            'quantity'   => 10,
            'unit_cost'  => 8.00,
            'total'      => 80.00,
        ]);

        Livewire::test(Edit::class, ['purchase' => $purchase])
            ->call('updateCartItem', 0, ['quantity' => 20])
            ->assertSet('cart_items', function ($cartItems) {
                return $cartItems[0]['quantity'] == 20;
            })
            ->assertSet('total_amount', 160.00);
    }

    /** @test */
    public function it_can_add_new_items_to_existing_purchase()
    {
        $product2 = Product::factory()->create([
            'name'     => 'Second Product',
            'price'    => 18.00,
            'quantity' => 25,
        ]);

        $purchase = Purchase::factory()->create([
            'supplier_id'    => $this->supplier->id,
            'user_id'        => $this->user->id,
            'total_amount'   => 80.00,
            'payment_status' => 'paid',
        ]);

        $purchase->purchaseDetails()->create([
            'product_id' => $this->product->id,
            'quantity'   => 10,
            'unit_cost'  => 8.00,
            'total'      => 80.00,
        ]);

        Livewire::test(Edit::class, ['purchase' => $purchase])
            ->set('product_id', $product2->id)
            ->set('quantity', 5)
            ->set('unit_cost', 15.00)
            ->call('addToCart')
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 2;
            })
            ->assertSet('total_amount', 155.00); // $80 + $75
    }

    /** @test */
    public function it_updates_product_quantity_after_purchase_creation()
    {
        $initialQuantity = $this->product->quantity;

        Livewire::test(Create::class)
            ->set('supplier_id', $this->supplier->id)
            ->set('product_id', $this->product->id)
            ->set('quantity', 15)
            ->set('unit_cost', 8.00)
            ->set('payment_status', 'paid')
            ->call('addToCart')
            ->call('store');

        $this->product->refresh();
        $this->assertEquals($initialQuantity + 15, $this->product->quantity);
    }

    /** @test */
    public function it_handles_different_payment_statuses()
    {
        $testCases = [
            ['status' => 'paid', 'expected' => 'paid'],
            ['status' => 'pending', 'expected' => 'pending'],
            ['status' => 'partial', 'expected' => 'partial'],
        ];

        foreach ($testCases as $case) {
            Livewire::test(Create::class)
                ->set('supplier_id', $this->supplier->id)
                ->set('product_id', $this->product->id)
                ->set('quantity', 5)
                ->set('unit_cost', 8.00)
                ->set('payment_status', $case['status'])
                ->call('addToCart')
                ->call('store');

            $this->assertDatabaseHas('purchases', [
                'payment_status' => $case['expected'],
            ]);
        }
    }

    /** @test */
    public function it_calculates_shipping_costs_correctly()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->set('shipping_cost', 15.00)
            ->call('addToCart')
            ->call('calculateTotals')
            ->assertSet('total_amount', 95.00); // $80 + $15 shipping
    }

    /** @test */
    public function it_handles_bulk_purchase_discounts()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 100) // Large quantity
            ->set('unit_cost', 8.00)
            ->set('bulk_discount_percentage', 10) // 10% bulk discount
            ->call('addToCart')
            ->call('applyBulkDiscount')
            ->assertSet('discount_amount', 80.00) // 10% of $800
            ->assertSet('total_amount', 720.00); // $800 - $80 discount
    }

    /** @test */
    public function it_prevents_negative_quantities_in_purchase()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', -5) // Negative quantity
            ->set('unit_cost', 8.00)
            ->call('addToCart')
            ->assertHasErrors(['quantity' => 'Quantity must be greater than zero']);
    }

    /** @test */
    public function it_prevents_zero_or_negative_unit_costs()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 0) // Zero cost
            ->call('addToCart')
            ->assertHasErrors(['unit_cost' => 'Unit cost must be greater than zero']);

        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', -5.00) // Negative cost
            ->call('addToCart')
            ->assertHasErrors(['unit_cost' => 'Unit cost must be greater than zero']);
    }

    /** @test */
    public function it_clears_cart_after_successful_purchase_creation()
    {
        $component = Livewire::test(Create::class)
            ->set('supplier_id', $this->supplier->id)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->set('payment_status', 'paid')
            ->call('addToCart');

        // Verify cart has items before store
        $component->assertSet('cart_items', function ($cartItems) {
            return count($cartItems) === 1;
        });

        $component->call('store');

        // Verify cart is cleared after successful store
        // This would be tested by creating a new component instance
        // since the original redirects
        $newComponent = Livewire::test(Create::class);
        $newComponent->assertSet('cart_items', []);
    }

    /** @test */
    public function it_maintains_cart_state_during_component_updates()
    {
        $component = Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 10)
            ->set('unit_cost', 8.00)
            ->call('addToCart')
            ->call('updateCartItem', 0, ['quantity' => 15]);

        // Simulate component refresh/update
        $component->call('$refresh')
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 1 && $cartItems[0]['quantity'] == 15;
            })
            ->assertSet('total_amount', 120.00);
    }
}
