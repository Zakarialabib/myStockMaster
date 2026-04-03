# Key Classes & Functions

This document outlines the core business logic handlers, traits, and services that power the backend operations of MyStockMaster.

## Actions

Actions encapsulate discrete business operations. They are strictly responsible for one outcome, making the system highly testable.

- `StoreSaleAction::execute()`: Handles the complex process of finalizing a sale. This includes creating the sale record, iterating over cart items to create sale details, deducting product stock, recording payments, and dispatching notifications.
- `StorePurchaseAction::execute()`: Manages incoming stock by creating a purchase record, iterating through purchased items, updating warehouse stock, calculating moving average costs, and updating supplier balances.
- `CalculateGrossMarginAction::execute()`: A finance action that computes the gross margin based on total revenue minus the cost of goods sold (COGS) within a given timeframe.
- `ManageCashFlowAction::execute()`: Calculates the total inflow (sales, incoming payments) and outflow (purchases, expenses) to provide a snapshot of the business's current liquidity.

## Services

Services provide reusable functionality across multiple controllers and Livewire components.

- `CartService::class`: The central service for the POS cart.
  - `add(Product $product, int $quantity)`: Adds an item to the session-based cart.
  - `updateQuantity(string $rowId, int $quantity)`: Updates cart item quantities.
  - `calculateTotals()`: Computes subtotal, tax, discount, and grand total.
- `DatabaseSyncService::class`: Used primarily for Desktop (NativePHP) synchronization, ensuring the local SQLite database matches the remote database or vice versa.
- `NotificationService::class`: Handles pushing notifications (e.g., Telegram, Database, Email) when stock is low or a payment is due.

## Traits

Traits are heavily utilized to avoid code duplication across Eloquent Models and Livewire components.

- `HasUuid`: Automatically generates a UUID for a model upon creation and sets the primary key type to string.
- `CartCalculationTrait`: Shared across `Pos\Index`, `Sales\Create`, and `Purchase\Create` to provide real-time reactive calculation of cart totals.
- `WithAlert`: A Livewire trait that provides simple wrapper methods (e.g., `$this->alert('success', 'Message')`) to trigger SweetAlert2 notifications on the frontend.
- `HasGlobalDate`: Adds date filtering scopes (e.g., `scopeDateBetween`) to models for reporting and analytics queries.

## Observers

- `ProductObserver::class`: Listens to the `Product` model. Upon saving or updating, it automatically clears the product cache to ensure the frontend reflects real-time data. It also triggers `UpdateProductCostHistory` jobs when the cost price changes.
- `SettingsObserver::class`: Clears system configuration caches whenever the global settings are updated.
