# Model Upgrade Plan to Laravel 12 & PHP 8.3

This document outlines the systematic plan to upgrade the application's domain models to leverage the latest features and best practices available in **Laravel 12** and **PHP 8.3**.

## 1. Implement Global Unguarding
Currently, all models define lengthy `$fillable` arrays. In modern Laravel applications, the standard practice is to unguard all models globally to reduce boilerplate, relying on FormRequests or DTOs for validation.

- **Action:** Remove the `$fillable` property from all models in `app/Models/`.
- **Action:** Add `Model::unguard();` inside the `boot()` method of `App\Providers\AppServiceProvider`.

## 2. Upgrade to Modern Casts
While some models use the new `protected function casts(): array` method introduced in Laravel 11, we need to standardize this across all models and remove any legacy `$casts` properties.

- **Action:** Find all instances of `protected $casts = [...]` and convert them to the `protected function casts(): array` method.
- **Action:** Utilize modern enum casting (e.g., `'status' => SaleStatus::class`) consistently.
- **Action:** Ensure native return types (`: array`) are applied to the `casts` method.

## 3. Leverage PHP 8.3 Native Types
PHP 8.3 introduces robust typing. We should replace PHPDoc annotations with native property types.

- **Action:** Update array properties like `public $orderable` and `public $filterable` to `public array $orderable` and `public array $filterable`.
- **Action:** Enforce strict typing (`declare(strict_types=1);`) in every model (many already have this, but ensure 100% coverage).
- **Action:** Add native return types to all relationship methods (e.g., `: BelongsTo`, `: HasMany`, `: MorphMany`).
- **Action:** Add scalar type hints and return types to custom model methods (e.g., `public function generateSlug(string $name): string`).

## 4. Modern Attribute Accessors & Mutators
Laravel 9+ introduced the `Attribute::make()` syntax for accessors and mutators, which many models already use. However, the closures inside them lack explicit return types.

- **Action:** Refactor all accessors and mutators to use `Attribute::make()`.
- **Action:** Add PHP 8.3 arrow functions with explicit parameter and return types for the `get` and `set` operations.
  - *Before:* `get: fn ($value) => $value / 100`
  - *After:* `get: fn (int|float|null $value): float => (float) $value / 100`

## 5. Simplify Model Booting
The `boot()` method is overridden in many models to hook into events (like `creating`).

- **Action:** Move logic from the `boot()` method into the more modern `booted()` method or define event-specific methods like `protected static function booted(): void`.
- **Action:** Alternatively, extract complex closure logic inside `creating` into dedicated Observer classes (e.g., `ProductObserver`, `SaleObserver`) to keep models clean.

## 6. Constant Modernization (PHP 8.3)
PHP 8.3 allows typed class constants. 

- **Action:** Add types to constants if they are heavily used.
  - *Example:* `public const array ATTRIBUTES = [...]` instead of just `public const ATTRIBUTES = [...]`.

## Execution Strategy
1. **Phase 1:** Update `AppServiceProvider` and bulk-remove `$fillable` properties across the `app/Models/` directory.
2. **Phase 2:** Run a script or perform regex replacements to convert `$casts` properties to the `casts()` method.
3. **Phase 3:** Manually review each model to add native return types to relationships and custom methods.
4. **Phase 4:** Standardize the `Attribute::make()` closures with explicit PHP 8.3 scalar types.
5. **Phase 5:** Run the application test suite to ensure no regressions were introduced by unguarding or strict typing.
