# Project Architecture

MyStockMaster is built as a monolithic application leveraging **Laravel v12**, **Livewire v4**, and **PHP 8.3**. It employs modern architectural patterns designed to handle robust business logic for Inventory Management, Point of Sale (POS), Accounting, and Reporting.

## High-Level Overview

1. **Monolithic MVC with Reactive UI:** The application adheres to the traditional Laravel MVC (Model-View-Controller) structure, but replaces standard blade controllers with reactive **Livewire components** for most of the application interface.
2. **Action-Oriented Logic:** Business logic is abstracted into dedicated `Action` classes (e.g., `StoreSaleAction`, `CalculateGrossMarginAction`) located in `app/Actions`. This promotes the Single Responsibility Principle and keeps Livewire components/Controllers lean.
3. **Service Layer:** Reusable domain operations are handled by services located in `app/Services` (e.g., `CartService`, `NotificationService`).
4. **Desktop Compatibility:** The project integrates with **NativePHP** (`nativephp/desktop`), allowing the web application to be bundled and distributed as a native desktop application using Electron/Tauri under the hood.

## Folder Structure Highlights

- `app/Actions/`: Contains isolated business logic routines (Analytics, Finance, Sales, Purchases).
- `app/Livewire/`: Contains all Livewire components, functioning as both the Controller and the reactive UI handler.
- `app/Models/`: Eloquent models mapping to the database, enhanced with global scopes and traits (e.g., `HasUuid`, `Trashed`).
- `app/Services/`: Reusable classes for caching, syncing, cart management, and notifications.
- `database/migrations/`: Includes comprehensive schemas for users, products, sales, purchases, warehouses, expenses, and analytics.

## Request Lifecycle

1. **Routing:** Incoming web requests are routed through `routes/web.php` directly to Livewire components or specific controllers.
2. **Middleware:** Custom middleware (e.g., `CheckInstallation`, `Locale`) intercepts requests to enforce installation checks and language preferences.
3. **Livewire Hydration:** Livewire v4 handles component mounting, state management, and real-time DOM updates via Vite-compiled assets.
4. **Action Execution:** Components trigger `Action` classes to process data (e.g., creating a sale and updating stock).
5. **Observers & Events:** Eloquent Observers (like `ProductObserver`) and Jobs handle side-effects such as sending notifications, updating price histories, or clearing caches.
