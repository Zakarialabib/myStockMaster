# Finance Orchestration Strategy Implementation Spec (Phase 2)

## Overview
This specification covers the second phase of the `ux_cx_finance_strategy.md` optimization. We will focus on standardizing the `ExpenseCategories` module to use the same Livewire Form object pattern and UI enhancements as the `Expense` module. Additionally, we will introduce speed optimizations and UX/CX improvements to the Finance Dashboards (`FinancialDashboard`, `KpiTracking`, `BreakEvenAnalysis`).

## Scope & Domains
1. **Expense Categories:**
   - `app/Livewire/ExpenseCategories/Create.php` & `Edit.php`
   - `app/Livewire/Forms/ExpenseCategoryForm.php` (New)
   - `resources/views/livewire/expense-categories/create.blade.php` & `edit.blade.php`
2. **Finance Analytics & Reports:**
   - `app/Livewire/Finance/KpiTracking.php`
   - `app/Livewire/Finance/BreakEvenAnalysis.php`
   - `app/Livewire/Finance/FinancialDashboard.php`

## Core Modernizations

### 1. Expense Categories Standardization
**Problem:** `Create` and `Edit` components duplicate validation logic and state. UI grids may be inconsistent.
**Solution:**
- Create `App\Livewire\Forms\ExpenseCategoryForm` extending `Livewire\Form`.
- Extract `$name` and `$description` properties with `#[Validate]` attributes.
- Refactor `Create.php` and `Edit.php` to inject `public ExpenseCategoryForm $form`.
- Update the blade views to use `wire:model="form.name"` and `form.description`. Ensure the grid uses standard `w-full` layouts (since it's a small form) and consistent labels.

### 2. Finance Dashboard Optimization (KpiTracking & BreakEvenAnalysis)
**Problem:** Queries inside `KpiTracking` and `BreakEvenAnalysis` often run multiple independent aggregates (e.g. `sum('total_amount')`, `count()`) causing multiple trips to the database. There is also a lack of caching for heavy historical data, impacting perceived speed.
**Solution:**
- **KpiTracking Query Optimization:** Combine queries. For instance, in `calculateRevenueKpis`, instead of separate `sum` and `count` queries, use a single `selectRaw('SUM(total_amount) as total_revenue, COUNT(id) as total_sales')` query.
- **Caching:** Wrap the historical/comparison KPI calculations (which do not change frequently) in `Cache::remember` blocks (e.g. 1-hour TTL for "previous" or "year_ago" metrics).
- **Date Filtering UX:** Ensure date inputs (`dateFrom`, `dateTo`) have appropriate Alpine/Livewire `.live.debounce` or rely on a "Generate Report" button to prevent massive query executions on every single keystroke.
- **Export Consistency:** Convert the `json_encode` stream downloads to standard Excel/PDF exports using `Maatwebsite\Excel` if feasible, or ensure the UI clearly indicates "Export JSON".

## Implementation Steps
1. **ExpenseCategoryForm:** Create the form object and refactor the CRUD components.
2. **ExpenseCategory UI:** Update Blade views to bind to the form and ensure standard modal styling.
3. **KpiTracking Optimization:** Refactor `calculateRevenueKpis`, `calculateProfitabilityKpis`, etc., to use single-pass aggregated queries. Implement `Cache::remember` for comparison periods.
4. **Testing:** Run standard `pint` formatting and verify no regressions in the tests.