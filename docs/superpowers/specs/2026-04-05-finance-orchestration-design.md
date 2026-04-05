# Finance UX/CX Orchestration Strategy Implementation Spec

## Overview
Based on the `ux_cx_finance_strategy.md`, this design specifies the architectural and UI enhancements required for the Finance module (Expenses). The focus is on removing friction, standardizing Livewire components, ensuring accessibility, and aligning the grid layouts.

## Scope & Domains
- `app/Livewire/Expense/Create.php` & `Edit.php`
- `resources/views/livewire/expense/create.blade.php` & `edit.blade.php`
- `app/Livewire/Forms/ExpenseForm.php` (New)

## Core Modernizations

### 1. Form Object Encapsulation (State Standardization)
**Problem:** Form state is scattered across the Create and Edit components, with duplicate validation rules.
**Solution:**
- Create `App\Livewire\Forms\ExpenseForm` extending `Livewire\Form`.
- Centralize `reference`, `category_id`, `date`, `amount`, `description`, `start_date`, `end_date`, `frequency`, `warehouse_id`, and `document`.
- Both `Create.php` and `Edit.php` will inject `public ExpenseForm $form;`.

### 2. UI/UX Consistency & Layout
**Problem:** The grid layouts differ between Create (`md:w-1/2`) and Edit (`xl:w-1/3 lg:w-1/2`). Label `for` attributes don't match `id`s. Missing placeholders.
**Solution:**
- Standardize both modals to use a clean `md:w-1/2` or `sm:w-full` split for standard inputs, and full width for descriptions and file uploads.
- Add default `<option value="">{{ __('Select...') }}</option>` placeholders for `Expense Category` and `Warehouse` in both views.
- Ensure all `<x-label for="xyz">` strictly match the input `<select id="xyz">` for accessibility.

### 3. Conditional UI (Alpine.js)
**Problem:** Recurring expense fields (`start_date` and `end_date`) are always visible even when `frequency` is 'none', creating visual clutter.
**Solution:**
- Wrap the recurring fields in an Alpine.js conditional block: `<div x-show="$wire.form.frequency !== 'none'">`.
- Ensure validation rules reflect this (i.e. only required if frequency != none).

### 4. Receipt / File Upload
**Problem:** Users cannot upload expense receipts.
**Solution:**
- Add `use WithFileUploads;` to the `Create` and `Edit` components.
- Add a file input for `form.document` to attach receipt images or PDFs.
- Update the store/update logic to save the file using Laravel's Storage facade and store the path in the `document` column of the `expenses` table.

### 5. Cash Register Interruption
**Problem:** In `Create.php`, if a user lacks an open cash register, they are interrupted with a modal dispatch, breaking their flow.
**Solution:**
- Remove the automatic modal dispatch `dispatch('createModal')->to(CashRegisterCreate::class);` from the `openCreateModal` initialization.
- Instead, display a non-blocking alert or an inline banner inside the modal, or simply prevent the submission gracefully with a descriptive error message if a cash register is strictly required.

## Implementation Steps
1. **Phase 1: Form Object:** Extract validation and state to `ExpenseForm`.
2. **Phase 2: Refactor Components:** Refactor `Expense/Create.php` and `Expense/Edit.php` to use the Form object and handle file uploads.
3. **Phase 3: Refactor Blade Views:** Standardize the modal layouts, fix labels, add placeholders, and implement Alpine.js conditional visibility for recurring fields.
4. **Phase 4: Testing:** Run `pint` and standard tests to verify the flow.