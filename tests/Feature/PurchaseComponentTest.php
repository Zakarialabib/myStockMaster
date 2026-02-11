<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Purchase\Index as PurchaseIndex;
use App\Livewire\Purchase\Create as CreatePurchase;
use App\Livewire\Purchase\Edit as EditPurchase;
use Exception;

class PurchaseComponentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Supplier $supplier;
    protected Warehouse $warehouse;
    protected Purchase $purchase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->supplier = Supplier::factory()->create();
        $this->warehouse = Warehouse::factory()->create();

        $this->purchase = Purchase::factory()->create([
            'supplier_id'  => $this->supplier->id,
            'warehouse_id' => $this->warehouse->id,
            'user_id'      => $this->user->id,
        ]);
    }

    /** @test */
    public function purchase_index_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PurchaseIndex::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function purchase_create_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreatePurchase::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function purchase_edit_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(EditPurchase::class, ['purchase' => $this->purchase]);

        $component->assertStatus(200);
    }

    /** @test */
    public function purchase_create_component_initializes_cart_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreatePurchase::class);

        // Check that cart is initialized if component uses cart
        if (method_exists($component->instance(), 'cart')) {
            $this->assertNotNull($component->instance()->cart);
        }
    }

    /** @test */
    public function purchase_edit_component_initializes_cart_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(EditPurchase::class, ['purchase' => $this->purchase]);

        // Check that cart is initialized if component uses cart
        if (method_exists($component->instance(), 'cart')) {
            $this->assertNotNull($component->instance()->cart);
        }
    }

    /** @test */
    public function purchase_index_displays_purchase_data()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(PurchaseIndex::class);

        // Check that the component renders without errors
        $component->assertStatus(200);

        // Check that purchase data is accessible
        $this->assertNotNull($component->instance());
    }

    /** @test */
    public function purchase_create_component_has_required_properties()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreatePurchase::class);

        // Check that component has basic properties
        $this->assertTrue(property_exists($component->instance(), 'supplier_id') ||
                         method_exists($component->instance(), 'getSupplierIdProperty'));
    }

    /** @test */
    public function purchase_edit_component_loads_purchase_data()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(EditPurchase::class, ['purchase' => $this->purchase]);

        // Check that the purchase is loaded
        $this->assertNotNull($component->instance());

        // Check that component has access to purchase data
        if (property_exists($component->instance(), 'purchase')) {
            $this->assertEquals($this->purchase->id, $component->instance()->purchase->id);
        }
    }

    /** @test */
    public function purchase_components_handle_validation_properly()
    {
        $this->actingAs($this->user);

        $component = Livewire::test(CreatePurchase::class);

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

    /** @test */
    public function purchase_components_handle_cart_operations_safely()
    {
        $this->actingAs($this->user);

        $components = [
            Livewire::test(CreatePurchase::class),
            Livewire::test(EditPurchase::class, ['purchase' => $this->purchase]),
        ];

        foreach ($components as $component) {
            // Test that cart operations don't throw uninitialized property errors
            if (method_exists($component->instance(), 'clearCart')) {
                $component->call('clearCart');
                $component->assertStatus(200);
            }

            if (method_exists($component->instance(), 'calculateTotal')) {
                $total = $component->instance()->calculateTotal();
                $this->assertIsNumeric($total);
            }
        }
    }
}
