# Application Domain Models

This document outlines the core domain models, their relationships, and the key architectural traits used across the `app/Models/` directory in this Laravel Point of Sale (POS) and E-commerce system.

## Domain Models Overview

The application is structured into several distinct domains, representing a complete POS/Inventory management system:

1. **Catalog & Products**
   - **`Product`**: The core entity representing an item for sale. It contains basic information (name, code, price, cost).
   - **`Category` & `Brand`**: Used to classify and group products.
   - **`ProductAttribute` & `AdjustedProduct`**: Manage product variations and stock adjustments.

2. **Inventory & Warehousing**
   - **`Warehouse`**: Physical or logical locations where products are stored.
   - **`ProductWarehouse` (Pivot)**: Manages stock levels per warehouse (`qty`, `price`, `cost`).
   - **`Movement`**: Tracks all stock movements (in/out) and morphs to various transaction types.
   - **`Transfer` & `TransferDetails`**: Handles moving stock between different warehouses.
   - **`Adjustment`**: Handles manual stock corrections.

3. **Sales & Orders**
   - **`Sale`**: Represents a completed or pending transaction with a customer.
   - **`SaleDetails`**: Line items for a specific sale.
   - **`SalePayment` & `SaleReturn`**: Manages the financial and reverse logistics of a sale.
   - **`Quotation`**: Pre-sales estimates provided to customers.

4. **Purchasing & Supply**
   - **`Purchase`**: Orders placed with suppliers to restock inventory.
   - **`PurchaseDetail`**: Line items for a purchase order.
   - **`Supplier`**: External vendors who provide products.
   - **`PurchasePayment` & `PurchaseReturn`**: Manages the financial and reverse logistics of a purchase.

5. **Users & Customers**
   - **`User`**: System administrators, cashiers, and managers.
   - **`Role` & `Permission`**: Manages access control (via Spatie).
   - **`Customer` & `CustomerGroup`**: Buyers interacting with the system.

6. **Finance & Operations**
   - **`CashRegister`**: Tracks drawer cash, opening/closing amounts.
   - **`Expense` & `ExpenseCategory`**: Tracks operational costs.
   - **`Currency` & `Setting`**: Global application configurations.

## Key Relationships

The models heavily leverage Eloquent relationships to stitch the domain together:
- **One-to-Many (`HasMany` / `BelongsTo`)**: A `Sale` belongs to a `Customer` and has many `SaleDetails`.
- **Many-to-Many (`BelongsToMany`)**: `Product` and `Warehouse` have a many-to-many relationship tracking specific stock and pricing per location.
- **Polymorphic (`MorphMany`)**: The `Movement` model morphs to any transaction that affects inventory (e.g., `Sale`, `Purchase`, `Transfer`).

## Key Traits

The models implement several custom and standard traits to enforce consistent behavior:

- **`HasAdvancedFilter` (`App\Support\HasAdvancedFilter`)**: Provides advanced dynamic filtering capabilities for API/DataTables. It processes global searches and complex multi-column filters automatically.
- **`HasUuid` (`App\Traits\HasUuid`)**: Replaces standard auto-incrementing integer IDs with universally unique identifiers (UUIDs) generated on the `creating` event.
- **`HasRoles` (`Spatie\Permission\Traits\HasRoles`)**: Applied to the `User` model to manage authorization.
- **`Notifiable`**: Standard Laravel trait for sending notifications via email, SMS, or database.
- **`SoftDeletes`**: Applied to critical models (like `Product`, `Sale`, `Purchase`) to ensure historical records are maintained even when records are "deleted".
