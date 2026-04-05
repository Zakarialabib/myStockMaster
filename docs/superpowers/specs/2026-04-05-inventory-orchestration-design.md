# UX/CX Inventory Orchestration Strategy Implementation Spec

## Overview
Based on the `ux_cx_inventory_strategy.md`, this implementation specification outlines the execution of optimization patterns for the Inventory module (Transfers, Products, and Warehouses). Our goal is to fix the destructive transfer logic, replace bloated modals with multi-step flows, and modernize the codebase strictly following Livewire v4 and PHP 8.3 practices.

## Scope & Domains
1. **Transfers**: 
   - Fix database schema mismatches.
   - Implement true Double-Entry logic for `ProductWarehouse`.
   - Apply Split-Pane layout to `app/Livewire/Transfer/Create.php`.
2. **Products**: 
   - Convert the massive single modal in `app/Livewire/Products/Create.php` into a structured, multi-step wizard.
   - Apply inline bulk action protections in `Products\Index.php`.
3. **Warehouses**:
   - Modernize standard CRUD utilizing Livewire `Form` objects.

## Architectural Decisions & Standards
- **Form Objects**: State will be encapsulated into `TransferForm`, `ProductForm`, and `WarehouseForm`.
- **Split-Pane Layout**: The `Transfer` flow will mirror the `Order Management` split-pane UI (Left: Context [Source/Dest Warehouses], Right: Interactive Cart).
- **Double-Entry Logic**: Transfers must use `decrement()` on the source `ProductWarehouse` and `firstOrCreate()->increment()` on the destination `ProductWarehouse`. The old logic (`$productWarehouse->update(['warehouse_id' => $this->to_warehouse_id])`) is destructive and will be removed.
- **Strict Typing**: All files will have `declare(strict_types=1);` and explicit property/return types.

## Step-by-Step Execution Plan

### Phase 1: Critical Transfer Logic & UI (Subagent 1)
- Create `App\Livewire\Forms\TransferForm`.
- Refactor `App\Livewire\Transfer\Create.php` to inject `TransferForm` and remove redundant state.
- Rewrite `store()` method to implement safe Double-Entry stock adjustments and match the `transfers` table schema exactly (avoiding SQL exceptions for non-existent columns like `user_id` if it's missing, though checking migrations first).
- Implement the CSS Grid split-pane layout in `resources/views/livewire/transfer/create.blade.php`.

### Phase 2: Product Creation Wizard (Subagent 2)
- Create `App\Livewire\Forms\ProductForm`.
- Refactor `App\Livewire\Products\Create.php` into a 3-step wizard (1. Definition, 2. Economics, 3. Logistics/Media).
- Create wizard UI in `resources/views/livewire/products/create.blade.php` utilizing Alpine.js for step navigation (`x-data="{ step: 1 }"`).

### Phase 3: Warehouses & Bulk Actions (Subagent 3)
- Create `App\Livewire\Forms\WarehouseForm`.
- Refactor `App\Livewire\Warehouses\Create.php` and `Edit.php` to use the form object.
- Implement "Preview" state for bulk price adjustments in `Products\Index.php` to prevent accidental mass modifications.

## Success Metrics
- Zero `QueryException` errors during Transfers.
- Accurate decrement/increment math in `ProductWarehouse` tables.
- Consistent UI alignment with the rest of the application (Order Management/POS).
- Code completely compliant with `pint --preset=laravel`.