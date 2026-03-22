# Upgrade to Laravel 12 & Livewire v4 - Backend Modernization

This document tracks the progress of modernizing the backend (Models, Migrations, Factories, and Seeders) to leverage the latest features of Laravel 12 and ensure full compatibility with Livewire v4.

## Comparison of Features (Current vs. Latest)

### Eloquent Models
| Feature | Current State | Laravel 12 Standard | Improvement Needed |
| --- | --- | --- | --- |
| Attribute Casting | `protected $casts = [...]` property | `protected function casts(): array` method | Move all casting to the `casts()` method for better flexibility and IDE support. |
| Accessors & Mutators | `getFooAttribute` / `setFooAttribute` | `Attribute::make(get: ..., set: ...)` | Refactor to modern `Attribute` return type for better type-hinting and consistency. |
| UUID Support | Mixed usage, some `HasUuid` traits | Native `HasUuids` (supporting UUIDv7) | Standardize on Laravel's native UUID support where applicable. |
| Model Hydration | Standard Eloquent | Livewire v4 Property Hydration | Ensure models support optimized hydration for Livewire v4. |

### Database Migrations
| Feature | Current State | Laravel 12 Standard | Improvement Needed |
| --- | --- | --- | --- |
| Class Definition | Mostly anonymous classes | Anonymous classes | Ensure all migrations use anonymous classes. |
| Foreign Keys | Mixed `integer` and `foreignId` | `foreignIdFor(Model::class)` | Use `foreignIdFor()` for better readability and relationship mapping. |
| Constraints | Manual constraint definition | `constrained()->onDelete(...)` | Use modern fluent constraints. |
| Schema Builders | Standard methods | Modern schema builders | Utilize `after()`, `dropped()`, etc. where appropriate. |

### Factories & Seeders
| Feature | Current State | Laravel 12 Standard | Improvement Needed |
| --- | --- | --- | --- |
| Factory Definitions | Standard `definition()` | States and Hooks | Implement more descriptive states and `afterCreating` hooks for complex relationships. |
| Seeder Patterns | Standard `call()` | Modern Seeder Patterns | Optimize seeding for realistic data and performance. |

## Modernization Tracking

### Models Refactored
- [x] app/Models/AdjustedProduct.php
- [x] app/Models/Adjustment.php
- [x] app/Models/Brand.php
- [x] app/Models/Cart.php
- [x] app/Models/CartItem.php
- [x] app/Models/CashRegister.php
- [x] app/Models/Category.php
- [x] app/Models/Currency.php
- [x] app/Models/Customer.php
- [x] app/Models/CustomerGroup.php
- [x] app/Models/EmailTemplate.php
- [x] app/Models/Expense.php
- [x] app/Models/ExpenseCategory.php
- [x] app/Models/Invoice.php
- [x] app/Models/Language.php
- [x] app/Models/Movement.php
- [x] app/Models/Notification.php
- [x] app/Models/Permission.php
- [x] app/Models/PriceHistory.php
- [x] app/Models/Printer.php
- [x] app/Models/Product.php
- [x] app/Models/ProductAttribute.php
- [x] app/Models/ProductWarehouse.php
- [x] app/Models/Purchase.php
- [x] app/Models/PurchaseDetail.php
- [x] app/Models/PurchasePayment.php
- [x] app/Models/PurchaseReturn.php
- [x] app/Models/PurchaseReturnDetail.php
- [x] app/Models/PurchaseReturnPayment.php
- [x] app/Models/Quotation.php
- [x] app/Models/QuotationDetails.php
- [x] app/Models/Role.php
- [x] app/Models/Sale.php
- [x] app/Models/SaleDetails.php
- [x] app/Models/SalePayment.php
- [x] app/Models/SaleReturn.php
- [x] app/Models/SaleReturnDetail.php
- [x] app/Models/SaleReturnPayment.php
- [x] app/Models/Setting.php
- [x] app/Models/Shipment.php
- [x] app/Models/Shipping.php
- [x] app/Models/Supplier.php
- [x] app/Models/Transfer.php
- [x] app/Models/TransferDetails.php
- [x] app/Models/User.php
- [x] app/Models/UserWarehouse.php
- [x] app/Models/Warehouse.php

### Migrations Modernized
- [x] All migrations updated to use anonymous classes.
- [x] Modern schema builders applied (e.g., `morphs()`, `foreignId()->constrained()`).

### Factories & Seeders Updated
- [x] Factories updated with descriptive states (e.g., `ProductFactory`).
- [x] Seeders maintained for compatibility.

## Livewire v4 Integration Notes
- Models updated to support optimized property hydration.
- Ensuring that all public properties in Livewire components that bind to models are properly handled by v4's new hydration engine.
