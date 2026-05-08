# Livewire v4 Analytics & Remaining Reports Design Spec

**Date**: 2026-04-27
**Status**: Approved
**Branch**: livewire-v4-enhancements

## 1. Executive Summary
This design specification focuses on modernizing the UI and deeply enhancing the data logic for the remaining 4 critical reports: `CustomersReport`, `SuppliersReport`, `WarehouseReport`, and `StockAlertReport`. By leveraging Livewire v4 `#[Computed]` properties and eager loading, we will resolve severe N+1 queries, introduce financial metrics (LTV, Debt, Payables, Valuation), and standardize the UI across the entire reporting suite.

## 2. Customers & Suppliers Reports (Debt & LTV Focus)

### 2.1 CustomersReport
- **Data Enhancements**:
  - Implement a `baseQuery()` method to centralize date and customer filters.
  - Add `#[Computed] public function ltv()` to calculate the **Customer Lifetime Value** (Total Sales Revenue).
  - Add `#[Computed] public function totalDueAmount()` to calculate **Total Outstanding Debt** from the customer.
- **UI Modernization**:
  - Wrap the view in `<x-page-container>` with breadcrumbs.
  - Move filters into `<x-slot name="filters">`.
  - Add KPI summary cards above the table to prominently display the `LTV` and `Total Due Amount`.

### 2.2 SuppliersReport
- **Data Enhancements**:
  - The current component is a skeleton. We will build it out using the `Purchases` model.
  - Implement a `baseQuery()` method for date and supplier filters.
  - Add `#[Computed] public function purchases()` to load paginated purchase records.
  - Add `#[Computed] public function totalPayables()` to calculate the **Total Amount Owed** to the supplier.
- **UI Modernization**:
  - Build the blade view from scratch using `<x-page-container>`.
  - Add a KPI summary card for `Total Payables`.
  - Implement a standard data table displaying Date, Reference, Supplier, Status, Total, Paid, Due, and Payment Status.

## 3. Warehouse & Stock Reports (Smart Inventory Focus)

### 3.1 WarehouseReport
- **Data Enhancements (N+1 Fixes)**:
  - The current `warehouseReport()` method loops through collections and executes a new database query for each record's details.
  - **Fix**: Eager load relationships in the `#[Computed]` methods (`purchases()->with('purchaseDetails')`, `sales()->with('saleDetails')`).
  - Add `#[Computed] public function stockValue()` to calculate and return the **Total Inventory Valuation** for the selected warehouse (utilizing the existing `qty * cost` logic on the pivot table).
- **UI Modernization**:
  - The current view is an empty placeholder (`{{-- Be like water. --}}`).
  - Build a comprehensive dashboard view inside `<x-page-container>`.
  - Add a massive KPI card for **Total Warehouse Valuation**.
  - Display summary tables or metrics for Sales, Purchases, and Expenses specific to that warehouse.

### 3.2 StockAlertReport
- **Data Enhancements (Warehouse-Specific Alerts)**:
  - Currently, the report evaluates the global `Product` model, ignoring that stock is maintained per warehouse in the `product_warehouse` pivot table.
  - **Fix**: Update the `stockAlert()` computed property to query the `ProductWarehouse` model instead of `Product`.
  - Eager load `with(['product', 'warehouse'])`.
  - Add a `$warehouse_id` filter property to allow managers to see alerts for specific locations.
  - Update the `setThreshold` method to accept a `ProductWarehouse` ID instead of a global `Product` ID.
- **UI Modernization**:
  - Wrap in `<x-page-container>`.
  - Add a Warehouse dropdown to the filters slot.
  - Add a "Warehouse" column to the data table (`$item->warehouse->name`).
  - Update row bindings to use `$item->product->name` and `$item->product->code`.