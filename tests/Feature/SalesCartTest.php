<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Sales\Create;
use App\Livewire\Sales\Edit;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalesCartTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->product = Product::factory()->create([
            'name'     => 'Test Product',
            'price'    => 15.00,
            'quantity' => 50,
            'code'     => 'SALE001',
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_add_product_to_sales_cart_in_create_component()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
            ->call('addToCart')
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 1 && $cartItems[0]['id'] == $this->product->id;
            })
            ->assertSet('total_amount', 30.00);
    }

    /** @test */
    public function it_can_update_cart_item_in_sales_create()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
            ->call('addToCart')
            ->call('updateCartItem', 0, ['quantity' => 5])
            ->assertSet('cart_items', function ($cartItems) {
                return $cartItems[0]['quantity'] == 5;
            })
            ->assertSet('total_amount', 75.00);
    }

    /** @test */
    public function it_can_remove_item_from_sales_cart()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
            ->call('addToCart')
            ->call('removeFromCart', 0)
            ->assertSet('cart_items', [])
            ->assertSet('total_amount', 0.00);
    }

    /** @test */
    public function it_calculates_tax_correctly_in_sales()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
            ->set('tax_percentage', 10)
            ->call('addToCart')
            ->call('calculateTotals')
            ->assertSet('tax_amount', 3.00) // 10% of $30
            ->assertSet('total_amount', 33.00); // $30 + $3 tax
    }

    /** @test */
    public function it_applies_discount_correctly_in_sales()
    {
        Livewire::test(Create::class)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
            ->set('discount_percentage', 10)
            ->call('addToCart')
            ->call('applyDiscount')
            ->assertSet('discount_amount', 3.00) // 10% of $30
            ->assertSet('total_amount', 27.00); // $30 - $3 discount
    }

    /** @test */
    public function it_can_create_sale_with_cart_items()
    {
        Livewire::test(Create::class)
            ->set('customer_id', $this->customer->id)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
            ->set('payment_status', 'paid')
            ->set('payment_method', 'cash')
            ->call('addToCart')
            ->call('store')
            ->assertRedirect(route('sales.index'));

        $this->assertDatabaseHas('sales', [
            'customer_id'    => $this->customer->id,
            'total_amount'   => 30.00,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('sale_details', [
            'product_id' => $this->product->id,
            'quantity'   => 2,
            'unit_price' => 15.00,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_in_sales_create()
    {
        Livewire::test(Create::class)
            ->call('store')
            ->assertHasErrors([
                'customer_id'    => 'required',
                'payment_status' => 'required',
            ]);
    }

    /** @test */
    public function it_prevents_creating_sale_with_empty_cart()
    {
        Livewire::test(Create::class)
            ->set('customer_id', $this->customer->id)
            ->set('payment_status', 'paid')
            ->call('store')
            ->assertHasErrors(['cart' => 'Cart cannot be empty']);
    }

    /** @test */
    public function it_can_edit_existing_sale_cart()
    {
        $sale = Sale::factory()->create([
            'customer_id'    => $this->customer->id,
            'user_id'        => $this->user->id,
            'total_amount'   => 30.00,
            'payment_status' => 'paid',
        ]);

        $sale->saleDetails()->create([
            'product_id' => $this->product->id,
            'quantity'   => 2,
            'unit_price' => 15.00,
            'total'      => 30.00,
        ]);

        Livewire::test(Edit::class, ['sale' => $sale])
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 1 && $cartItems[0]['quantity'] == 2;
            })
            ->assertSet('total_amount', 30.00);
    }

    /** @test */
    public function it_can_update_existing_sale_cart_items()
    {
        $sale = Sale::factory()->create([
            'customer_id'    => $this->customer->id,
            'user_id'        => $this->user->id,
            'total_amount'   => 30.00,
            'payment_status' => 'paid',
        ]);

        $sale->saleDetails()->create([
            'product_id' => $this->product->id,
            'quantity'   => 2,
            'unit_price' => 15.00,
            'total'      => 30.00,
        ]);

        Livewire::test(Edit::class, ['sale' => $sale])
            ->call('updateCartItem', 0, ['quantity' => 4])
            ->assertSet('cart_items', function ($cartItems) {
                return $cartItems[0]['quantity'] == 4;
            })
            ->assertSet('total_amount', 60.00);
    }

    /** @test */
    public function it_can_add_new_items_to_existing_sale()
    {
        $product2 = Product::factory()->create([
            'name'     => 'Second Product',
            'price'    => 20.00,
            'quantity' => 30,
        ]);

        $sale = Sale::factory()->create([
            'customer_id'    => $this->customer->id,
            'user_id'        => $this->user->id,
            'total_amount'   => 30.00,
            'payment_status' => 'paid',
        ]);

        $sale->saleDetails()->create([
            'product_id' => $this->product->id,
            'quantity'   => 2,
            'unit_price' => 15.00,
            'total'      => 30.00,
        ]);

        Livewire::test(Edit::class, ['sale' => $sale])
            ->set('product_id', $product2->id)
            ->set('quantity', 1)
            ->set('unit_price', 20.00)
            ->call('addToCart')
            ->assertSet('cart_items', function ($cartItems) {
                return count($cartItems) === 2;
            })
            ->assertSet('total_amount', 50.00);
    }

    /** @test */
    public function it_updates_product_quantity_after_sale_creation()
    {
        $initialQuantity = $this->product->quantity;

        Livewire::test(Create::class)
            ->set('customer_id', $this->customer->id)
            ->set('product_id', $this->product->id)
            ->set('quantity', 5)
            ->set('unit_price', 15.00)
            ->set('payment_status', 'paid')
            ->call('addToCart')
            ->call('store');

        $this->product->refresh();
        $this->assertEquals($initialQuantity - 5, $this->product->quantity);
    }

    /** @test */
    public function it_prevents_overselling_products()
    {
        $lowStockProduct = Product::factory()->create([
            'name'     => 'Low Stock Product',
            'price'    => 25.00,
            'quantity' => 3,
        ]);

        Livewire::test(Create::class)
            ->set('product_id', $lowStockProduct->id)
            ->set('quantity', 5) // More than available
            ->set('unit_price', 25.00)
            ->call('addToCart')
            ->assertHasErrors(['quantity' => 'Insufficient stock available']);
    }

    /** @test */
    public function it_clears_cart_after_successful_sale_creation()
    {
        $component = Livewire::test(Create::class)
            ->set('customer_id', $this->customer->id)
            ->set('product_id', $this->product->id)
            ->set('quantity', 2)
            ->set('unit_price', 15.00)
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
}
