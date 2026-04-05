# POS Livewire 3/4 & Component Style Modernization Design

## Overview
The recent UX/CX POS refactor successfully introduced new workflows and orchestration (e.g. `CustomerCombobox`, `SmartCashButtons`, optimistic UI), but it carried over or introduced technical debt regarding Livewire v3/v4 best practices. This design outlines the modernization of the POS components to match the project's standards, optimize performance, and properly utilize Livewire's latest features.

## Scope
This design covers the Point of Sale (POS) Livewire components and their immediate dependencies.
- `app/Livewire/Pos/Index.php`
- `app/Livewire/Pos/CustomerCombobox.php`
- `app/Livewire/Products/SearchProduct.php`
- Associated Blade views.

## Current Issues & Friction Points
1. **State Bloat & Unused Properties**: `Index.php` defines numerous properties (`$quantity`, `$price`, `$item_discount`, `$data`, `$product`, etc.) that are completely unused, needlessly increasing the Livewire payload size. The same applies to `SearchProduct.php` (`$product`, `$search_results`).
2. **Missing Form Objects**: Checkout state (`$customer_id`, `$warehouse_id`, `$total_amount`, `$paid_amount`, etc.) is declared directly on the component rather than encapsulated in a `Livewire\Form` object, which is the project's standard (e.g. `SaleForm`, `PurchaseForm`).
3. **Inefficient Computed Properties**: `Index.php` has a `#[Computed] public function customers()` that fetches all customers into memory, which is a massive N+1/memory leak and defeats the purpose of the newly introduced `CustomerCombobox`.
4. **Outdated Component Communication**: `CustomerCombobox` uses `$this->dispatch('customer-selected')` instead of Livewire v3's `#[Modelable]`, which is the modern standard for two-way binding nested components.
5. **Missing `#[Computed]`**: `CustomerCombobox` uses standard getter methods instead of `#[Computed]` for its customer list and selected name.

## Proposed Architecture & Solutions

### 1. Form Object Integration
Create `App\Livewire\Forms\PosCheckoutForm` to encapsulate all checkout-related state and validation.
- Moves properties like `customer_id`, `warehouse_id`, `tax_percentage`, `discount_percentage`, `shipping_amount`, `total_amount`, `paid_amount`, `payment_method`, and `note` into the form object.
- `Index.php` will simply inject `public PosCheckoutForm $form;`.

### 2. State Cleanup
- Remove all unused array and mixed properties from `Index.php` (`$quantity`, `$check_quantity`, `$price`, `$discount_type`, `$item_discount`, `$data`, `$product`, `$discount_amount`, `$tax_amount`).
- Remove `$product` and `$search_results` from `SearchProduct.php`.
- Remove the unused `#[Computed] public function customers()` from `Index.php`.

### 3. Component Communication (`#[Modelable]`)
- Refactor `CustomerCombobox` to use `#[Modelable]` for the selected customer ID.
- Parent component (`Index.blade.php`) will bind it via `<livewire:pos.customer-combobox wire:model="form.customer_id" />`.
- This eliminates the need for manual event dispatching and listening for customer selection.

### 4. Computed Properties
- Add `#[Computed]` to `getCustomers()` in `CustomerCombobox.php`.
- Add `#[Computed]` to `getSelectedCustomerName()` in `CustomerCombobox.php`.

## Implementation Requirements
- All PHP files touched must maintain `declare(strict_types=1);`.
- Constructor property promotion must be used where applicable.
- Types must be explicitly defined.
- Run `vendor/bin/pint --dirty --format agent` after modifications.

## Success Metrics
- **Payload Size**: Reduction in Livewire request payload size by removing unused state.
- **Memory Usage**: Elimination of the "fetch all customers" query on mount/render.
- **Maintainability**: Standardization of the POS component with the rest of the application's Livewire v3/v4 practices.

## Next Steps
Once this POS modernization is implemented and verified, the same UX/CX orchestration and Livewire modernization strategies will be sequentially applied to other domains (Inventory, Finance, Settings).
