# Major Modules & Responsibilities

MyStockMaster is divided into several highly cohesive modules built using Livewire components and Laravel Actions. Each module targets a specific domain in an ERP/POS environment.

## Point of Sale (POS) & Sales
**Namespace:** `App\Livewire\Pos`, `App\Livewire\Sales`
- **Responsibilities:** 
  - Handles the core Point of Sale interface.
  - Manages active carts via `CartService` and `CartManager`.
  - Calculates discounts, taxes, and final totals.
  - Finalizes sales and records payments via `StorePosSaleAction` and `StoreSaleAction`.
  - Manages returns and quotations (`SaleReturn`, `Quotations`).

## Inventory & Products
**Namespace:** `App\Livewire\Products`, `App\Livewire\Warehouses`, `App\Livewire\Transfer`
- **Responsibilities:**
  - Complete lifecycle management of products (creation, updates, deletion).
  - Handles barcodes, price history, and promotional prices.
  - Multi-warehouse operations (transfers, adjustments, user-warehouse associations).
  - Stock alerts and inventory tracking (`AdjustedProduct`, `Movement`).

## Purchases & Suppliers
**Namespace:** `App\Livewire\Purchase`, `App\Livewire\Suppliers`
- **Responsibilities:**
  - Creating and tracking purchase orders from suppliers.
  - Updating stock based on incoming shipments.
  - Managing supplier details, balances, and payment dues (`PayDue.php`).
  - Handling purchase returns.

## Analytics & Finance
**Namespace:** `App\Livewire\Analytics`, `App\Livewire\Finance`, `App\Actions\Finance`
- **Responsibilities:**
  - Real-time financial dashboards and KPI tracking.
  - Calculates Break-Even Points, Cash Flow, Gross/Net Margins, and Expansion Readiness.
  - Revenue reporting and product-level analytics.
  - Generates comprehensive PDF and Excel exports.

## HR & Customer Management
**Namespace:** `App\Livewire\Users`, `App\Livewire\Customers`
- **Responsibilities:**
  - Manages employee profiles, roles, and permissions (Spatie Laravel Permission).
  - Tracks customer data, purchase history, and groupings (Customer Groups).
  - Prints customer catalogs and tracks outstanding payments.

## System Settings & Utilities
**Namespace:** `App\Livewire\Settings`, `App\Livewire\Backup`
- **Responsibilities:**
  - Configuration of system-wide settings, invoicing themes, languages, currencies, and SMTP settings.
  - Database backups and synchronization (`DatabaseSync`, `BackupCommand`).
  - Desktop integration logs and NativePHP specific functions.
