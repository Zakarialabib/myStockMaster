# Database Layer Improvement Plan (Laravel 12 / PHP 8.3)

## 1. Migration Modernization
While the codebase already uses anonymous migrations (a great start!), there are further opportunities to modernize the schema definitions for Laravel 12:
- **Return Types:** Ensure all `up()` and `down()` methods declare `void` return types (`public function up(): void`).
- **Fluent Foreign Keys:** Replace legacy manual foreign key definitions found in newer migrations (like `carts_table.php`) with modern, fluent syntax.
  - *Current:* `$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');`
  - *Proposed:* `$table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->cascadeOnDelete();`
- **Primary Key Standardization:** The application currently mixes auto-incrementing integers (`$table->id()`) and UUIDs (`$table->uuid('id')->primary()`). Consider standardizing on UUIDs or moving to **ULIDs** (`$table->ulid('id')`) which offer the uniqueness of UUIDs with the sortability of auto-incrementing IDs (native in modern Laravel).

## 2. Factory Enhancements (PHP 8.3)
Factories should be updated to strictly adhere to PHP 8.3 typing standards:
- **Method Return Types:** Add explicit `array` return types to the `definition()` methods across all 11 factories.
  ```php
  public function definition(): array
  ```
- **Static State Types:** Add `static` return types to state modifier methods (e.g., `unverified(): static`).
- **PHP 8.3 Syntax:** Utilize newer PHP features like named arguments for complex faker calls and array unpacking where multiple states are combined.

## 3. Seeder Optimizations
- **Strict Method Typing:** Ensure all `run()` methods inside the 20 seeder classes declare a `void` return type.
- **Idempotent Operations:** In bulk seeders (like `ProductsSeeder` which uses direct `DB::table()->insert()`), transition to `upsert()` or `insertOrIgnore()` to make the seeders safely re-runnable without causing duplicate key constraints.
- **Class Modifiers:** For any custom helper classes used within seeders, utilize PHP 8.3 `readonly` classes or strongly typed properties.

## 4. Schema & Data Type Optimizations
- **Financial Precision:** Review columns storing financial data. Currently, tables mix `decimal` (e.g., `carts` table) and other formats. It is an industry best practice to store monetary values as `integer` (cents) to prevent floating-point arithmetic errors, using Laravel custom casts or accessor/mutators to handle display logic.
- **JSON Column Typing:** For columns like `options` in `products`, ensure that Laravel 12's native schema assertions or database-level JSON validations are applied if supported by the underlying database engine (MySQL 8+/PostgreSQL).

## 5. Ongoing Enforcement
- **Strict Types Verification:** All 88 PHP files in the database directory currently have `declare(strict_types=1);`. Ensure tooling (like PHPStan or Laravel Pint) is configured to enforce this on all new files automatically.