# Order Management UX/CX Modernization Design

## Overview
Based on the `ux_cx_order_management_strategy.md`, this design defines the architectural approach to modernizing the Sales and Purchase flows. The focus is to implement proven Livewire v4 standard practices without changing the established visual design system (not mimicking POS/Ecommerce, but utilizing the existing back-office layout). We will incorporate zero-breaking-change improvements to order orchestration, focusing heavily on inline operations and split-pane structural enhancements.

## Scope
- `app/Livewire/Sales/Index.php` & `Create.php`
- `app/Livewire/Purchase/Index.php` & `Create.php`
- Associated Datatable and Form Blade views.

## Core Modernizations

### 1. Inline Status Dropdowns (Index Views)
**Friction Point:** Currently, changing a `status` or `payment_status` on an order requires navigating to the Edit page, changing the dropdown, and saving.
**Solution:**
- Introduce a lightweight `<x-table.status-dropdown>` Blade component.
- This component will be integrated directly into the Datatable columns for `status` and `payment_status`.
- It will trigger a Livewire method (e.g., `updateStatus($id, $newStatus)`) in the `Index` component.
- **Backend requirement:** Updating status must be handled carefully. If a purchase goes from 'Pending' to 'Received', inventory must be updated. We will create or utilize existing Actions to safely orchestrate this.

### 2. Split-Pane Order Creation (Create Views)
**Friction Point:** The cart and order details are currently disconnected, sometimes requiring modal toggles or scrolling down past large forms.
**Solution:**
- Refactor the `resources/views/livewire/sales/create.blade.php` and `purchase/create.blade.php` into a unified CSS grid split-pane.
- **Left Pane (40%):** Order metadata (Customer/Supplier, Date, Reference, Status, Note).
- **Right Pane (60%):** The interactive cart, product search, and financial summary (Totals, Tax, Discount, Shipping).
- Both panes will scroll independently if needed, keeping the summary always visible.
- Utilize the newly modernized `CustomerCombobox` and `SupplierCombobox` with `#[Modelable]` to keep the parent component clean.

### 3. State Standardization (Livewire Forms)
**Friction Point:** Massive property bloat on the `Create` and `Edit` components (`$global_discount`, `$tax`, `$shipping_amount`, etc.).
**Solution:**
- Utilize the existing `App\Livewire\Forms\SaleForm` and `PurchaseForm`.
- If they don't exist or lack properties, we will define them rigorously with `#[Validate]` attributes.
- The `Create` components will simply inject `public SaleForm $form;`.

### 4. Keyboard Shortcuts & Auto-Save
- Introduce `@keydown.window.ctrl.s.prevent="$wire.store()"` on the Create views.
- Add an Alpine listener to auto-calculate totals optimistically before Livewire syncs.

## Implementation Steps
1. **Forms**: Ensure `SaleForm` and `PurchaseForm` encapsulate all order data.
2. **Components**: Refactor `Sales/Create` and `Purchase/Create` to use the Form objects and clean up redundant `$cart` sync methods.
3. **Views**: Implement the CSS Grid Split-Pane layout for the Create views using standard Tailwind classes (`grid grid-cols-1 lg:grid-cols-12 gap-6`, `col-span-4`, `col-span-8`).
4. **Index Inline Editing**: Add `updateSaleStatus(int $id, string $status)` and `updatePaymentStatus(int $id, string $status)` methods to `Sales/Index.php`. Render the Alpine dropdown in the table cell.
5. **Testing**: Run standard `pint` and `php artisan test` to verify no regressions in the core Sales/Purchase flows.

## Success Metrics
- Zero-breaking-change integration with existing Sales/Purchase models.
- Reduction of clicks required to update order status from 4 to 2.
- Visually persistent order summary during product entry.