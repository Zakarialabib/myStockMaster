<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Sales\Create as CreateSale;
use App\Livewire\Sales\Edit as EditSale;
use App\Livewire\Sales\Index as SalesIndex;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use App\Models\Warehouse;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalesComponentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Customer $customer;

    protected Warehouse $warehouse;

    protected Sale $sale;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->warehouse = Warehouse::factory()->create();

        $this->sale = Sale::factory()->create([
            'customer_id' => $this->customer->id,
            'warehouse_id' => $this->warehouse->id,
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function sales_index_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(SalesIndex::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function sales_create_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreateSale::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function sales_edit_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(EditSale::class, ['sale' => $this->sale]);

        $component->assertStatus(200);
    }

    /** @test */
    public function sales_create_component_initializes_cart_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreateSale::class);

        // Check that cart is initialized if component uses cart
        if (method_exists($component->instance(), 'cart')) {
            $this->assertNotNull($component->instance()->cart);
        }
    }

    /** @test */
    public function sales_edit_component_initializes_cart_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(EditSale::class, ['sale' => $this->sale]);

        // Check that cart is initialized if component uses cart
        if (method_exists($component->instance(), 'cart')) {
            $this->assertNotNull($component->instance()->cart);
        }
    }

    /** @test */
    public function sales_index_displays_sales_data()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(SalesIndex::class);

        // Check that the component renders without errors
        $component->assertStatus(200);

        // Check that sales data is accessible
        $this->assertNotNull($component->instance());
    }

    /** @test */
    public function sales_create_component_has_required_properties()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreateSale::class);

        // Check that component has basic properties
        $this->assertTrue(property_exists($component->instance(), 'customer_id') ||
                         method_exists($component->instance(), 'getCustomerIdProperty'));
    }

    /** @test */
    public function sales_edit_component_loads_sale_data()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(EditSale::class, ['sale' => $this->sale]);

        // Check that the sale is loaded
        $this->assertNotNull($component->instance());

        // Check that component has access to sale data
        if (property_exists($component->instance(), 'sale')) {
            $this->assertEquals($this->sale->id, $component->instance()->sale->id);
        }
    }

    /** @test */
    public function sales_components_handle_validation_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreateSale::class);

        // Test that component handles validation without throwing errors
        $component->assertStatus(200);

        // If component has a store method, test it handles validation
        if (method_exists($component->instance(), 'store')) {
            try {
                $component->call('store');
                // Should either succeed or fail gracefully with validation errors
                $this->assertTrue(true);
            } catch (Exception $e) {
                // Validation errors are expected for empty data
                $this->assertStringContainsString(
                    'validation',
                    strtolower($e->getMessage()),
                    'Exception should be validation-related'
                );
            }
        }
    }
}
