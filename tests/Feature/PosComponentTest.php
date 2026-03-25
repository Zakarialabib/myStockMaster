<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Pos\Index as PosIndex;
use App\Models\CashRegister;
use App\Models\Customer;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PosComponentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Customer $customer;

    protected Warehouse $warehouse;

    protected CashRegister $cashRegister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->warehouse = Warehouse::factory()->create();

        $this->cashRegister = CashRegister::factory()->create([
            'user_id' => $this->user->id,
            'warehouse_id' => $this->warehouse->id,
            'status' => true,
        ]);
    }

    /** @test */
    public function pos_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function pos_component_initializes_cart_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        // Check that cart is initialized
        $this->assertNotNull($component->instance()->cart);

        // Check that cart is empty initially
        $this->assertTrue($component->instance()->cart->isEmpty());
    }

    /** @test */
    public function pos_component_sets_default_values_on_mount()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        $component->assertSet('global_discount', 0)
            ->assertSet('global_tax', 0)
            ->assertSet('tax_percentage', 0)
            ->assertSet('discount_percentage', 0)
            ->assertSet('shipping_amount', 0)
            ->assertSet('paid_amount', 0)
            ->assertSet('payment_method', 'cash');
    }

    /** @test */
    public function pos_component_can_calculate_total()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        $component->set('shipping_amount', 10);

        $total = $component->instance()->calculateTotal();

        $this->assertIsNumeric($total);
    }

    /** @test */
    public function pos_component_can_clear_cart()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        $component->call('resetCart');

        $this->assertTrue($component->instance()->cart->isEmpty());
    }

    /** @test */
    public function pos_component_requires_customer_for_checkout()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        $component->set('customer_id', null)
            ->call('proceed');

        // Should show error message for missing customer
        $component->assertDispatched('alert');
    }

    /** @test */
    public function pos_component_opens_checkout_modal_with_valid_customer()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PosIndex::class);

        $component->set('customer_id', $this->customer->id)
            ->call('proceed');

        $component->assertSet('checkoutModal', true);
    }
}
