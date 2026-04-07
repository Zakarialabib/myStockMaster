# Livewire v4 Mastery Phase 2: Full Ecosystem Utilization

## Overview
This specification details the second phase of our Livewire v4 architecture upgrade. Based on the framework's native capabilities, we will aggressively remove redundant boilerplate (like manual `$loading` properties and external JavaScript) and replace it with Livewire's built-in features (`wire:loading`, `#[Lazy]`, `->navigate()`, `@teleport`, and `wire:poll`).

The goal is to create a zero-dependency, hyper-optimized Single Page Application (SPA) experience across the entire project.

## 1. Global SPA Form Transitions (`Redirecting` & `wire:navigate`)
Currently, when a user successfully creates a record (e.g., `CustomerService::create`), the Livewire component redirects them back to the index page using a standard `redirect()->route('customers.index')`. This triggers a full browser reload.
**Action:** Append `->navigate()` to all `redirect()->route(...)` calls within Livewire component methods (`Create`, `Edit`).
**Files Affected:**
- `app/Livewire/Customers/Create.php`, `Edit.php`
- `app/Livewire/Products/Create.php`, `Edit.php`
- `app/Livewire/Sales/Create.php`, `Edit.php`
- `app/Livewire/Purchase/Create.php`, `Edit.php`
- All other `Create` and `Edit` components handling redirects.

## 2. Native Loading States & Lazy Rendering (`Loading States` & `#[Lazy]`)
Heavy components like `FinancialDashboard`, `AnalyticsDashboard`, and `KpiTracking` currently define a `public function placeholder()` but lack the `#[Lazy]` attribute, making them load synchronously and block the initial page render. Furthermore, they manually manage a `$this->loading` boolean property.
**Action:** 
- Add the `#[Lazy]` attribute to all heavy dashboard and report components.
- Delete the `public bool $loading = false;` property and all `$this->loading = true/false` assignments from these components.
- In their respective blade views, use `<div wire:loading>...</div>` and `wire:target="loadFinancialData"` to natively handle loading UI without manual state management.
**Files Affected:**
- `app/Livewire/Finance/FinancialDashboard.php`
- `app/Livewire/Finance/KpiTracking.php`
- `app/Livewire/Analytics/AnalyticsDashboard.php`
- `app/Livewire/Analytics/ProductAnalytics.php`
- `app/Livewire/Analytics/RevenueReports.php`

## 3. Global Modal Escaping (`@teleport`)
The application currently has over 140 usages of the `<x-modal>` component. When placed deep within tables or nested cards, modals can occasionally be visually clipped by `overflow: hidden` or trapped by lower `z-index` contexts.
**Action:** Wrap the core markup inside `resources/views/components/modal.blade.php` with Livewire's native `@teleport('#modals-container')`. This instantly fixes all clipping and z-index issues globally by pushing the modal DOM to the bottom of the `<body>` element.
**Files Affected:**
- `resources/views/components/modal.blade.php`

## 4. Real-Time Background Widgets (`wire:poll`)
Widgets like the `NotificationBell` currently require a full page refresh to fetch new data (unless an internal event is dispatched).
**Action:** Add `wire:poll.60s="loadNotifications"` to the root `<div>` of the `notification-bell.blade.php` component. This will silently ping the server every 60 seconds to fetch new notifications, providing a real-time experience with zero custom JavaScript.
**Files Affected:**
- `resources/views/livewire/notifications/notification-bell.blade.php`

## 5. File Downloads
Ensure all exports utilize Livewire's native `return response()->streamDownload()` capabilities, fully leveraging the work done in the previous architectural phase where exports were migrated to `FromQuery`. No structural changes needed, just strict adherence to the pattern.