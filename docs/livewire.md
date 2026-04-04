# Livewire Architecture Documentation

This document provides an overview of the Livewire architecture, component structure, and state management in the `app/Livewire` directory.

## 1. Architecture Overview
The application follows a **feature-based** (or domain-based) architecture, grouping Livewire components by their business domain. This makes it highly organized and scalable.

Key domains include:
- **Sales & Purchases** (`Sales`, `PurchaseReturn`, etc.)
- **Dashboards & KPIs** (`Dashboard`, `Stats`)
- **Inventory Management** (`Warehouses`, `Categories`, `Adjustment`)
- **Reporting** (`Reports`)
- **Settings & User Management** (`Settings`, `Users`, `Language`)
- **Utils** (`Utils`): Contains reusable traits and utility components like `Datatable`, `Sidebar`, `ProductCart`, etc.

The architecture extensively utilizes Livewire v3+ features such as `#[Layout]`, `#[Title]`, `#[On]`, `#[Computed]`, and `#[Lazy]` to manage layout integration, event listening, and optimized rendering.

## 2. Component Structure
Components are largely categorized into:
- **Index Components** (e.g., `Sales/Index.php`): Responsible for listing data. They typically import the `Datatable` trait to manage sorting, filtering, and pagination.
- **Form Components** (e.g., `Sales/Create.php`, `Categories/Edit.php`): Manage user input. They often contain large sets of public properties representing the form fields and use the `WithModels` trait or custom traits (like `LivewireCartTrait`) to manage relationships and business logic.
- **Widget Components** (e.g., `Dashboard/KpiCards.php`, `Dashboard/Transactions.php`): Read-heavy components used for analytics. They heavily use `#[Computed(persist: true)]` to cache expensive database queries and `#[Lazy]` to defer loading.
- **Utility Traits** (e.g., `Datatable`, `WithAlert`, `WithModels`): Used to share common functionality across multiple components, reducing code duplication.

## 3. State Management
State management is currently handled through a combination of approaches:

- **Flat Component State**: Most form components (like `Sales/Create.php`) declare all their form fields as individual `public` properties on the component class itself.
- **Trait-based State**: Shared state logic (such as Cart items or Datatable filters) is injected via traits (`LivewireCartTrait` manages cart items, totals, discounts; `Datatable` manages pagination options, search strings, sorting directions).
- **Computed Properties**: Read-only state that is derived from the database (e.g., dashboard statistics) is managed via the `#[Computed]` attribute. This ensures the data is not serialized and sent back and forth between the client and server, saving bandwidth.
- **Event-Driven Reactivity**: Components communicate with each other using Livewire's event system via the `#[On('eventName')]` attribute (e.g., refreshing a datatable when an item is deleted, or opening a modal).
- **Caching**: Heavy computed properties utilize Laravel's `Cache::flexible()` or Livewire's native `persist: true` parameter within `#[Computed]` to maintain state across requests without hitting the database repeatedly.

## 4. UX Patterns & Enhancements
Recent improvements have introduced several modern UX patterns using a combination of Livewire and Alpine.js to provide a snappy, app-like experience.

### Slide-over Cart
To maximize the screen real estate available for product search and selection, the Sales and Purchase interfaces (Create/Edit) utilize an Alpine.js-driven slide-over drawer (off-canvas) for the cart and checkout form. This allows users to seamlessly browse products while keeping the cart accessible at a moment's notice.

### Optimistic Alpine.js Updates
The Product Cart features optimistic UI updates powered by Alpine.js. By utilizing Alpine's `$watch` and Livewire's `$wire.entangle()`, calculations for quantity, price, discounts, and taxes are performed instantly on the client side. This provides immediate visual feedback to the user while Livewire processes the authoritative background update asynchronously.

### Searchable Comboboxes
Standard dropdowns for relational data selection (such as Customers in Sales and Suppliers in Purchases) have been upgraded to searchable Comboboxes. These Alpine.js-driven components allow users to quickly filter and select records from large datasets without leaving the keyboard or scrolling through endless native select options.

### Global Barcode Scanner Integration
The Barcode Scanner functionality has been refactored to be completely unobtrusive. It employs global Alpine `window` keydown listeners to capture rapid barcode scans automatically. This means the user no longer needs to manually focus a specific search input field before scanning a product, greatly accelerating the checkout process.