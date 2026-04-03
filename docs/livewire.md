# Livewire Architecture Documentation

This document provides an overview of the Livewire architecture, component structure, and state management in the `app/Livewire` directory. It has been updated to reflect the new standardized patterns introduced in the Livewire v4 refactoring.

## 1. Architecture Overview
The application follows a **feature-based** (or domain-based) architecture, grouping Livewire components by their business domain. This makes it highly organized and scalable.

Key domains include:
- **Sales & Purchases** (`Sales`, `PurchaseReturn`, etc.)
- **Dashboards & KPIs** (`Dashboard`, `Stats`)
- **Inventory Management** (`Warehouses`, `Categories`, `Adjustment`)
- **Reporting** (`Reports`)
- **Settings & User Management** (`Settings`, `Users`, `Roles`, `Permissions`)
- **Utils** (`Utils`): Contains reusable traits and utility components like `Datatable`, `Sidebar`, `ProductCart`, etc.

The architecture extensively utilizes modern Livewire v4+ features such as `#[Layout]`, `#[Title]`, `#[On]`, `#[Computed]`, and `#[Lazy]` to manage layout integration, event listening, and optimized rendering.

## 2. Component Structure
Components are largely categorized into:
- **Index Components** (e.g., `Sales/Index.php`): Responsible for listing data. They typically import the `Datatable` trait to manage sorting, filtering, and pagination.
- **Form Components** (e.g., `Sales/Create.php`, `Categories/Edit.php`): Manage user input. They encapsulate validation and data via dedicated **Form Objects**.
- **Widget Components** (e.g., `Dashboard/KpiCards.php`, `Dashboard/Transactions.php`): Read-heavy components used for analytics. They heavily use `#[Computed]` to cache expensive database queries and `#[Lazy]` to defer loading.
- **Utility Traits** (e.g., `Datatable`, `WithAlert`, `WithModels`): Used to share common functionality across multiple components, reducing code duplication.

## 3. Standardized Component Patterns (v4+)
As part of the Livewire v4 refactor, the following patterns are strictly enforced:

### 3.1 PHP 8.3 Typing & Syntax
- **Strict Typing:** All properties and methods must declare explicit types. Nullable types must use the `?Type` syntax.
- **Method Signatures:** Use modern PHP 8.3 features, including readonly properties and typed arrays where applicable.
- **Attributes Over Methods:** Use Livewire attributes (e.g., `#[Title]`, `#[Layout]`, `#[On]`) instead of overriding legacy lifecycle methods where possible.

### 3.2 Form Objects
For complex data entry, components should extract their state and validation logic into dedicated **Form Objects** (`Livewire\Form`).
- **Example:** `App\Livewire\Forms\UserForm` instead of cluttering the component with individual properties and `$rules`.
- Keeps the component lean and solely focused on handling UI interactions and rendering.

### 3.3 State & Security Attributes
- **`#[Computed]`:** Used for read-only state derived from the database. It prevents the data from being serialized and sent back and forth between the client and server. Heavy properties utilize caching mechanisms like `persist: true` or Laravel's `Cache::flexible()`.
- **`#[Locked]`:** Applied to properties that should not be manipulated by the client-side (e.g., entity IDs, primary keys in edit forms). This prevents malicious users from altering critical identifiers.
- **`#[Lazy]`:** Applied to components (like charts or heavy reports) to defer their loading until the initial page is fully rendered, improving Time to First Byte (TTFB).

## 4. Naming Conventions
To maintain consistency across the codebase:
- **Components:** PascalCase (e.g., `CreateSale`, `DashboardKpis`).
- **Directories:** PascalCase representing the domain (e.g., `App/Livewire/Sales`).
- **Blade Views:** kebab-case, corresponding to the component name (e.g., `create-sale.blade.php`).
- **Form Objects:** Suffixed with `Form` (e.g., `SettingsForm`, `UserForm`).
- **Export Controllers:** Controllers dedicated to exports should be named `Export[Entity]Controller` and implement an `__invoke` method.

## 5. UX & Management Concepts
The UI/UX interactions in Blade templates have been upgraded to match Livewire v4 best practices:

### 5.1 Reactive Directives
- **`wire:model.live`:** Used for real-time reactivity (e.g., search bars, instant validation) replacing the older `wire:model` defaults.
- **`wire:model.blur`:** Used for inputs where real-time validation isn't necessary, waiting until the user clicks away.

### 5.2 JavaScript Integration
- **`@script` and `$wire`:** Custom JS logic interacting with the component should be wrapped in `@script` directives and utilize `$wire` to call component methods directly from the frontend without emitting global events.
- **Event-Driven Reactivity:** Components communicate via the `#[On('eventName')]` attribute. Emitting events is the preferred way to refresh sibling components, such as updating a datatable after a modal form completes.

### 5.3 Export Workflows
Exports (PDF/Excel) are decoupled from the Livewire components to avoid memory exhaustion issues:
- Livewire Index components provide an action button (e.g., `ExportAll` or `ExportSelected`).
- These actions redirect to a dedicated **Export Controller** which handles the streaming or downloading of the file outside of the Livewire lifecycle.

## 6. Development Workflow
When creating a new component:
1. Generate it using `php artisan make:livewire Domain/ComponentName`.
2. Apply `#[Title]` and `#[Layout]` attributes.
3. If it's a form, create an accompanying `Form` object.
4. Ensure all state that shouldn't be edited by the client uses `#[Locked]`.
5. Use `#[Computed]` for any data fetched for rendering.
6. Verify styling using the latest Tailwind CSS conventions and ensure JS logic is enclosed in `@script`.
