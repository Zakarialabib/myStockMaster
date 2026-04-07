# Livewire v4 Mastery Phase 2 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Roll out advanced Livewire v4 features (`->navigate()`, `#[Lazy]`, `@teleport`, `wire:poll`) globally to optimize performance, remove JS dependencies, and finalize the SPA architecture.

**Architecture:** 
- Convert full-page redirects to SPA transitions via `->navigate()`.
- Eliminate manual `$loading` state properties in dashboards by leveraging native `#[Lazy]` components and `wire:loading` UI directives.
- Globally fix z-index modal trapping by wrapping the base modal component in `@teleport`.
- Introduce zero-JS background polling for the notification bell.

**Tech Stack:** Laravel 11/12, Livewire v3/v4

---

### Task 1: Global SPA Form Transitions (`->navigate()`)

**Files:**
- Modify: `app/Livewire/Customers/Create.php`, `app/Livewire/Customers/Edit.php`
- Modify: `app/Livewire/Products/Create.php`, `app/Livewire/Products/Edit.php`
- Modify: `app/Livewire/Sales/Create.php`, `app/Livewire/Sales/Edit.php`
- Modify: `app/Livewire/Purchase/Create.php`, `app/Livewire/Purchase/Edit.php`
- Modify: `app/Livewire/Categories/Create.php`, `app/Livewire/Categories/Edit.php`
- Modify: `app/Livewire/Brands/Create.php`, `app/Livewire/Brands/Edit.php`
- Modify: `app/Livewire/Warehouses/Create.php`, `app/Livewire/Warehouses/Edit.php`
- Modify: `app/Livewire/Quotations/Create.php`, `app/Livewire/Quotations/Edit.php`

- [x] **Step 1: Write a script to append `->navigate()` to all `redirect()->route()` calls**
Instead of manually editing dozens of files, run a robust regex search and replace to safely append `->navigate()` to any component returning a route redirect.

```bash
# Find all redirect()->route(...) calls in Livewire components and append ->navigate()
find app/Livewire -type f -name "*.php" -exec sed -i 's/return redirect()->route(\(.*\));/return redirect()->route(\1)->navigate();/g' {} +
```

- [x] **Step 2: Run test to verify syntax hasn't broken**
Run: `php artisan test`
Expected: PASS

- [x] **Step 3: Commit**
```bash
git add app/Livewire/
git commit -m "feat: upgrade all component redirects to use native SPA navigate()"
```

### Task 2: Native Loading States & Lazy Rendering (`#[Lazy]`)

**Files:**
- Modify: `app/Livewire/Finance/FinancialDashboard.php`
- Modify: `app/Livewire/Finance/KpiTracking.php`
- Modify: `app/Livewire/Analytics/AnalyticsDashboard.php`
- Modify: `app/Livewire/Analytics/ProductAnalytics.php`
- Modify: `app/Livewire/Analytics/RevenueReports.php`
- Modify: `app/Livewire/Finance/BreakEvenAnalysis.php`
- Modify: `app/Livewire/Dashboard.php`

- [ ] **Step 1: Add `#[Lazy]` and remove `$loading` properties**
For each file listed above:
1. Import `use Livewire\Attributes\Lazy;`
2. Add `#[Lazy]` above the class declaration.
3. Remove `public bool $loading = false;` (or similar).
4. Remove `$this->loading = true;` and `$this->loading = false;` from all methods (like `loadFinancialData()`, `mount()`, etc.).

- [ ] **Step 2: Run test to verify changes**
Run: `php artisan test`
Expected: PASS

- [ ] **Step 3: Commit**
```bash
git add app/Livewire/Finance/ app/Livewire/Analytics/ app/Livewire/Dashboard.php
git commit -m "refactor: remove manual loading state and implement native #[Lazy] loading for heavy dashboards"
```

### Task 3: Global Modal Escaping (`@teleport`)

**Files:**
- Modify: `resources/views/components/modal.blade.php`

- [ ] **Step 1: Wrap modal in `@teleport`**
Open `resources/views/components/modal.blade.php`.
Wrap the outermost `<div>` (the one with `x-data="appModal(...)"`) in the `@teleport('#modals-container')` directive.

```html
<!-- At the top of the file, after the @php block -->
@teleport('#modals-container')
<div x-data="appModal({
    show: @entangle($attributes->wire('model')),
    // ...
})" ...>
    <!-- existing modal content -->
</div>
@endteleport
```

- [ ] **Step 2: Commit**
```bash
git add resources/views/components/modal.blade.php
git commit -m "fix: teleport all modals globally to escape z-index and overflow trapping"
```

### Task 4: Real-Time Background Widgets (`wire:poll`)

**Files:**
- Modify: `resources/views/livewire/notifications/notification-bell.blade.php`
- Modify: `resources/views/livewire/sync-status.blade.php` (if it exists)

- [ ] **Step 1: Add `wire:poll` to Notification Bell**
Open `resources/views/livewire/notifications/notification-bell.blade.php`.
Find the outermost `<div>` and append `wire:poll.60s="loadNotifications"`.

```html
<!-- Before -->
<div>
    <x-dropdown align="right" width="80">

<!-- After -->
<div wire:poll.60s="loadNotifications">
    <x-dropdown align="right" width="80">
```

- [ ] **Step 2: Add `wire:poll` to Sync Status**
Open `resources/views/livewire/sync-status.blade.php`.
Find the outermost `<div x-data="{ show: @entangle('showModal') }">` or similar wrapper and append `wire:poll.120s="checkSyncStatus"`.

```html
<!-- Example After -->
<div wire:poll.120s="checkSyncStatus" ...>
```

- [ ] **Step 3: Commit**
```bash
git add resources/views/livewire/notifications/ resources/views/livewire/sync-status.blade.php
git commit -m "feat: implement native wire:poll for real-time widget updates"
```