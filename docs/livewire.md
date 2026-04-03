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