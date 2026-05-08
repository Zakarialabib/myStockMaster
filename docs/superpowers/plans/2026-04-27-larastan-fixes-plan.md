# Larastan Static Analysis Fixes Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Resolve all PHPStan Level 6 errors reported in `larastan-output.md` for the Analytics and Reports namespaces.

**Architecture:** 
- Add strict typing to Action class parameters (`Product $model`, `Builder $baseQuery`).
- Fix improper component property types (`array` vs `Collection`).
- Fix invalid method calls and parameters in Livewire components (`completed()` scope, `__invoke` signatures).

**Tech Stack:** Laravel, PHP 8.1+, Larastan

---

### Task 1: Fix Action Class Types

**Files:**
- Modify: `app/Actions/Analytics/AnalyzePriceTrendsAction.php`
- Modify: `app/Actions/Analytics/GenerateProductAnalyticsAction.php`
- Modify: `app/Actions/Analytics/GenerateRevenueReportAction.php`

- [ ] **Step 1: Fix AnalyzePriceTrendsAction**

Add `\Illuminate\Database\Eloquent\Model` type to `$model` parameters.

```php
    // In app/Actions/Analytics/AnalyzePriceTrendsAction.php
    
    // Line ~134
    private function performAnalysis(\Illuminate\Database\Eloquent\Model $model, int $days): array
    
    // Line ~231
    private function validateModel(mixed $model): void
    
    // Line ~243
    private function getCacheKey(\Illuminate\Database\Eloquent\Model $model, int $days): string
```

- [ ] **Step 2: Fix GenerateProductAnalyticsAction**

Update `$productId` to accept `string|int`, and type `$baseQuery`.

```php
    // In app/Actions/Analytics/GenerateProductAnalyticsAction.php
    
    // Line ~21
    private function generateCacheKey(int|string $productId, array $dateRange): string

    // Line ~81
    private function getSalesStatistics(\Illuminate\Database\Eloquent\Builder $baseQuery): array

    // Line ~102
    private function getPerformanceMetrics(\Illuminate\Database\Eloquent\Builder $baseQuery, int $days): array

    // Line ~153
    private function getProfitabilityAnalysis(\Illuminate\Database\Eloquent\Builder $baseQuery, \App\Models\Product $product): array
```

- [ ] **Step 3: Fix GenerateRevenueReportAction**

Remove the nullsafe operator before `??`.

```php
    // In app/Actions/Analytics/GenerateRevenueReportAction.php
    // Line ~261
    'product_name' => $item->product->name ?? 'Unknown Product',
```

- [ ] **Step 4: Commit**

```bash
git commit -a -m "fix(analytics): resolve Larastan type hints and nullsafe operator errors in Action classes"
```

### Task 2: Fix Livewire Component Invocations & Properties

**Files:**
- Modify: `app/Livewire/Analytics/AnalyticsDashboard.php`
- Modify: `app/Livewire/Analytics/ProductAnalytics.php`

- [x] **Step 1: Fix AnalyticsDashboard invocations and types**

Fix the `GenerateProductAnalyticsAction` call to pass an array, and fix `$priceTrends` to accept a Collection.

```php
    // In app/Livewire/Analytics/AnalyticsDashboard.php
    
    // Fix property type (Line ~20)
    public \Illuminate\Support\Collection|array $priceTrends = [];
    
    // Fix invocation (Line ~87)
    $productStats = resolve(GenerateProductAnalyticsAction::class)(
        $product,
        ['start' => $dateFrom, 'end' => $dateTo]
    );
```

- [x] **Step 2: Fix ProductAnalytics property type**

```php
    // In app/Livewire/Analytics/ProductAnalytics.php
    
    // Fix property type (Line ~100 or where it's defined)
    public \Illuminate\Support\Collection|array $priceTrends = [];
```

- [x] **Step 3: Commit**

```bash
git commit -a -m "fix(analytics): resolve Larastan action invocations and property types in dashboards"
```

### Task 3: Fix ProfitLossReport Scope

**Files:**
- Modify: `app/Livewire/Reports/ProfitLossReport.php`

- [x] **Step 1: Replace `completed()` with `where()`**

The `completed()` method is a local scope on the `Sale` model but is failing static analysis when called dynamically.

```php
    // In app/Livewire/Reports/ProfitLossReport.php
    // Line ~183 (inside the query builder)
    
    // Replace:
    // ->completed()
    // With:
    ->where('status', \App\Enums\SaleStatus::COMPLETED)
```

- [x] **Step 2: Commit**

```bash
git commit -a -m "fix(reports): replace dynamic completed scope with explicit where clause for Larastan"
```