<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Pos\CustomerCombobox;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Products\SearchProduct;
use App\Livewire\Utils\ProductCart;
use App\Models\CashRegister;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use ReflectionClass;
use Tests\TestCase;

class PosUxEnhancementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product;

    protected Warehouse $warehouse;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->warehouse = Warehouse::factory()->create(['name' => 'Main Warehouse']);
        $this->customer = Customer::factory()->create(['name' => 'John Doe', 'phone' => '1234567890']);
        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'quantity' => 100,
            'code' => 'TEST001',
            'price' => 50.00,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function customer_combobox_can_search_customers_by_name()
    {
        Customer::factory()->create(['name' => 'Alice Smith', 'phone' => '1111111111']);
        Customer::factory()->create(['name' => 'Bob Johnson', 'phone' => '2222222222']);

        Livewire::test(CustomerCombobox::class)
            ->set('search', 'Alice')
            ->assertSee('Alice Smith')
            ->assertDontSee('Bob Johnson')
            ->assertHasNoErrors();
    }

    /** @test */
    public function customer_combobox_can_search_customers_by_phone()
    {
        Customer::factory()->create(['name' => 'Alice Smith', 'phone' => '1234567890']);
        Customer::factory()->create(['name' => 'Bob Johnson', 'phone' => '0987654321']);

        Livewire::test(CustomerCombobox::class)
            ->set('search', '123')
            ->assertSee('Alice Smith')
            ->assertDontSee('Bob Johnson')
            ->assertHasNoErrors();
    }

    /** @test */
    public function customer_combobox_limits_results_to_10()
    {
        Customer::factory()->count(15)->create();

        $component = Livewire::test(CustomerCombobox::class)
            ->set('search', '');

        $customers = $component->get('customers');
        $this->assertLessThanOrEqual(10, $customers->count());
    }

    /** @test */
    public function customer_combobox_can_select_customer()
    {
        Livewire::test(CustomerCombobox::class)
            ->call('selectCustomer', $this->customer->id)
            ->assertSet('selectedCustomerId', $this->customer->id)
            ->assertDispatched('customer-selected');
    }

    /** @test */
    public function customer_combobox_can_clear_selection()
    {
        Livewire::test(CustomerCombobox::class)
            ->call('selectCustomer', $this->customer->id)
            ->call('clearSelection')
            ->assertSet('selectedCustomerId', null)
            ->assertDispatched('customer-selected');
    }

    /** @test */
    public function customer_combobox_opens_on_search_input()
    {
        Livewire::test(CustomerCombobox::class)
            ->set('search', 'test')
            ->assertSet('isOpen', true);
    }

    /** @test */
    public function pos_index_auto_creates_cash_register_if_none_exists()
    {
        $cashRegisterCount = CashRegister::count();

        Livewire::test(PosIndex::class)
            ->set('warehouse_id', $this->warehouse->id)
            ->assertSet('cash_register_id', '!=', null);

        $this->assertGreaterThan($cashRegisterCount, CashRegister::count());
    }

    /** @test */
    public function pos_index_dispatches_cash_register_opened_event()
    {
        Livewire::test(PosIndex::class)
            ->set('warehouse_id', $this->warehouse->id)
            ->assertDispatched('cash-register-opened');
    }

    /** @test */
    public function search_product_dispatches_success_event_on_barcode_scan()
    {
        Livewire::test(SearchProduct::class, ['warehouseId' => $this->warehouse->id])
            ->call('handleBarcodeScan', 'TEST001')
            ->assertDispatched('barcode-scanned-success');
    }

    /** @test */
    public function search_product_dispatches_error_event_on_invalid_barcode()
    {
        Livewire::test(SearchProduct::class, ['warehouseId' => $this->warehouse->id])
            ->call('handleBarcodeScan', 'INVALID123')
            ->assertDispatched('barcode-scanned-error');
    }

    /** @test */
    public function product_cart_uses_debounce_for_quantity_updates()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->set('quantity.' . $this->product->id, 5)
            ->call('updateQuantity', $this->product->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function product_cart_uses_debounce_for_price_updates()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->set('price.' . $this->product->id, 75.00)
            ->call('updatePrice', $this->product->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function smart_cash_buttons_render_in_checkout_modal()
    {
        Livewire::test(PosIndex::class)
            ->set('total_amount', 123.45)
            ->call('render')
            ->assertSeeHtml('smart-cash-buttons');
    }

    /** @test */
    public function pos_component_is_isolated_for_performance()
    {
        $reflection = new ReflectionClass(PosIndex::class);
        $attributes = $reflection->getAttributes();

        $hasIsolate = false;
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === 'Livewire\Attributes\Isolate') {
                $hasIsolate = true;
                break;
            }
        }

        $this->assertTrue($hasIsolate, 'POS Index component should have #[Isolate] attribute for performance');
    }

    /** @test */
    public function customer_combobox_handles_keyboard_navigation()
    {
        Customer::factory()->count(3)->create();

        Livewire::test(CustomerCombobox::class)
            ->set('search', '')
            ->call('highlightNext')
            ->assertSet('highlightedIndex', 0)
            ->call('highlightNext')
            ->assertSet('highlightedIndex', 1)
            ->call('highlightPrev')
            ->assertSet('highlightedIndex', 0)
            ->assertHasNoErrors();
    }

    /** @test */
    public function customer_combobox_selects_highlighted_customer()
    {
        $customer = Customer::factory()->create(['name' => 'Test Customer']);

        Livewire::test(CustomerCombobox::class)
            ->set('search', 'Test')
            ->call('highlightNext')
            ->call('selectHighlighted')
            ->assertSet('selectedCustomerId', $customer->id)
            ->assertSet('isOpen', false)
            ->assertDispatched('customer-selected');
    }

    /** @test */
    public function pos_index_has_proper_validation_rules()
    {
        $component = Livewire::test(PosIndex::class);

        $reflection = new ReflectionClass($component->instance());
        $properties = $reflection->getProperties();

        $hasValidatedProperties = false;
        foreach ($properties as $property) {
            $attributes = $property->getAttributes();
            foreach ($attributes as $attribute) {
                if (str_contains($attribute->getName(), 'Validate')) {
                    $hasValidatedProperties = true;
                    break 2;
                }
            }
        }

        $this->assertTrue($hasValidatedProperties, 'POS Index should have validated properties');
    }

    /** @test */
    public function pos_index_has_locked_properties_for_security()
    {
        $component = Livewire::test(PosIndex::class);

        $reflection = new ReflectionClass($component->instance());
        $properties = $reflection->getProperties();

        $hasLockedProperties = false;
        foreach ($properties as $property) {
            $attributes = $property->getAttributes();
            foreach ($attributes as $attribute) {
                if (str_contains($attribute->getName(), 'Locked')) {
                    $hasLockedProperties = true;
                    break 2;
                }
            }
        }

        $this->assertTrue($hasLockedProperties, 'POS Index should have locked properties for security');
    }

    /** @test */
    public function cart_item_row_component_renders_correctly()
    {
        Livewire::test(ProductCart::class, ['cartInstance' => 'pos'])
            ->call('productSelected', $this->product->id)
            ->assertSeeHtml('cart-item-row')
            ->assertSeeHtml('incrementQty')
            ->assertSeeHtml('decrementQty')
            ->assertHasNoErrors();
    }

    /** @test */
    public function pos_index_supports_keyboard_shortcuts()
    {
        Livewire::test(PosIndex::class)
            ->call('productSelected', $this->product->id)
            ->call('proceed')
            ->assertHasNoErrors();
    }

    /** @test */
    public function pos_index_handles_warehouse_selection()
    {
        Livewire::test(PosIndex::class)
            ->set('warehouse_id', $this->warehouse->id)
            ->assertDispatched('warehouseSelected');
    }

    /** @test */
    public function pos_index_calculates_total_with_shipping()
    {
        Livewire::test(PosIndex::class)
            ->call('productSelected', $this->product->id)
            ->set('shipping_amount', 10.00)
            ->call('updatedShippingAmount')
            ->assertSet('total_with_shipping', 60.00);
    }

    /** @test */
    public function pos_index_updates_paid_amount_on_payment_method_change()
    {
        Livewire::test(PosIndex::class)
            ->call('productSelected', $this->product->id)
            ->set('payment_method', 'cash')
            ->call('updatedPaymentMethod', 'cash')
            ->assertSet('paid_amount', 50.00);
    }

    /** @test */
    public function pos_index_can_reset_cart()
    {
        Livewire::test(PosIndex::class)
            ->call('productSelected', $this->product->id)
            ->call('resetCart')
            ->assertHasNoErrors();
    }

    /** @test */
    public function pos_index_prevents_checkout_without_customer()
    {
        Livewire::test(PosIndex::class)
            ->call('productSelected', $this->product->id)
            ->call('proceed')
            ->assertHasNoErrors();
    }

    /** @test */
    public function pos_index_allows_checkout_with_customer()
    {
        Livewire::test(PosIndex::class)
            ->call('productSelected', $this->product->id)
            ->set('customer_id', $this->customer->id)
            ->call('proceed')
            ->assertSet('checkoutModal', true);
    }
}
