# Master Plan Path: MyStockMaster Architecture Upgrades

This document compiles the step-by-step improvement plans generated for each layer of the application. By following this path, the MyStockMaster codebase will be fully upgraded to leverage **Laravel 12**, **Livewire v4 Island Architecture**, and **PHP 8.3** best practices.

## Phase 1: Database & Models Modernization
*Goal: Ensure the foundational data layer is strictly typed, secure, and performant.*

### Step 1.1: Global Model Unguarding & Type Strictness ✅
- **Action:** Added `Model::unguard()` to `AppServiceProvider`'s `boot()` method.
- **Action:** Removed `$fillable` arrays from all models in `app/Models/`.
- **Action:** Ensured `declare(strict_types=1);` is present in all models and enforced PHP 8.3 native types for properties.

### Step 1.2: Modernizing Casts & Attributes ✅
- **Action:** Converted legacy `$casts` properties to the new Laravel 11/12 `casts(): array` method across all models.
- **Action:** Refactored accessors and mutators to use the modern `Attribute::make()` syntax with strictly typed PHP 8.3 arrow functions.

### Step 1.3: Database Schema & Seeder Enhancements ✅
- **Action:** Updated migrations to use fluent foreign key definitions (e.g., `$table->foreignIdFor(User::class)->constrained()`) and ensured `up()`/`down()` methods return `void`.
- **Action:** Updated factory `definition()` methods to return `array`.
- **Action:** Optimized bulk seeders to use `insertOrIgnore()` for idempotency and added `void` to all `run()` methods.

---

## Phase 2: Controllers & Routes Refactoring
*Goal: Clean up the HTTP layer, adopt route attributes, and modernize API responses.*

### Step 2.1: Route Attributes ✅
- **Action:** Migrated route definitions from `routes/web.php` and `routes/api.php` directly onto controller methods using PHP 8 attributes (e.g., `#[Get]`, `#[Post]`).

### Step 2.2: Invokable Controllers & Form Requests ✅
- **Action:** Refactored multi-action controllers that handle disparate tasks (like `ExportController`) into single-responsibility Invokable Controllers (`ExportSaleController`, etc.).
- **Action:** Extracted inline `$request->validate()` logic into dedicated `FormRequest` classes.

### Step 2.3: API Resource Standardization ✅
- **Action:** Removed the legacy `BaseController` used for formatting API responses.
- **Action:** Standardized all API endpoints to return native Eloquent API Resources (e.g., `ProductResource::collection()`).

---

## Phase 3: Livewire Island Architecture
*Goal: Maximize frontend performance, encapsulate state, and reduce server payload sizes.*

### Step 3.1: Component Isolation (`#[Isolate]`) ✅
- **Action:** Applied the `#[Isolate]` attribute to heavy, independent components (e.g., Dashboard widgets, modals, POS components) to prevent full-page diffing and waterfall re-renders.

### Step 3.2: Extract Form Objects ✅
- **Action:** Identified massive form components (e.g., `Sales/Create`, `Purchase/Create`) that manage dozens of public properties.
- **Action:** Extracted these properties and their `#[Validate]` rules into dedicated `Livewire\Form` classes (`SaleForm`, `PurchaseForm`, etc.).

### Step 3.3: Computed Properties & Lazy Loading ✅
- **Action:** Replaced public properties that hold Eloquent Collections with `#[Computed]` methods to drastically reduce network payload sizes.
- **Action:** Applied the `#[Lazy]` attribute to all data-heavy tables (Index views) and report components to improve initial page load times (TTFB).

---

## Execution Guidelines
To execute this plan efficiently:
1. **Iterate by Phase:** Complete and commit Phase 1 before moving to Phase 2. This ensures the foundational layers (Models/DB) are stable before the HTTP/UI layers consume them.
2. **Run Tests Frequently:** After each step, run `php artisan test` and `vendor/bin/pint` to ensure no regressions occur and code style remains consistent.
3. **Focus on High-Impact Areas First:** In Phase 3, prioritize refactoring the POS (`Pos/Index`) and Sales components, as they are the most complex and will benefit the most from Island Architecture.