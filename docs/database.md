# Database Architecture & Documentation

## Overview

The database is designed to support a robust POS (Point of Sale), Inventory Management, and E-commerce system. The architecture relies heavily on standard Laravel conventions, utilizing anonymous migrations and strict typing to ensure type safety and schema consistency. Primary keys are widely implemented as UUIDs rather than auto-incrementing integers, providing enhanced security, uniqueness across distributed systems, and preventing enumeration attacks.

## Key Tables

The schema is normalized and separated into distinct logical domains:

### 1. User & Access Management
- **`users`**: Core user table using UUIDs. Includes fields for authentication, contact details, role assignment, and multi-warehouse access configurations.
- **`sessions` & `password_reset_tokens`**: Standard Laravel authentication tables.
- **`permission_tables`**: Handles role-based access control (RBAC), mapping users to roles and roles to specific system permissions.

### 2. Inventory & Product Catalog
- **`products`**: Central catalog table (UUID primary key). Contains product metadata, taxonomy (category, brand), and flags for features like ecommerce visibility.
- **`categories` & `brands`**: Taxonomy tables for organizing products.
- **`warehouses`**: Physical or logical locations where inventory is stored.
- **`product_warehouse`**: Pivot table managing stock levels, costs, and prices per product per warehouse.
- **`adjustments` & `adjusted_products`**: Tracks manual inventory count adjustments and the specific products affected.

### 3. Sales, Purchases & Transactions
- **`sales` & `sale_details`**: Records customer orders, tax, shipping, and individual line items sold.
- **`purchases` & `purchase_details`**: Records inbound inventory from suppliers and corresponding line items.
- **`sale_payments` & `purchase_payments`**: Tracks payment transactions against specific sales or purchases.
- **`sale_returns` & `purchase_returns`**: Manages RMA (Return Merchandise Authorization) and inventory clawbacks.
- **`quotations`**: Stores estimates provided to customers before converting to a sale.

### 4. Entities (CRM & SRM)
- **`customers` & `customer_groups`**: Client directory and tier-based grouping (for varied pricing or discounts).
- **`suppliers`**: Vendor directory for procurement.

### 5. System Configuration & Operations
- **`settings`**: Global system configurations (e.g., currency, multi-warehouse flags, installation status).
- **`currencies` & `languages`**: Localization and financial formatting parameters.
- **`expenses` & `expense_categories`**: Operational cost tracking, including recurring expenses.

## Seeding Strategy

The system employs a dual-strategy seeding approach, controlled via the `COMPREHENSIVE_SEEDING` environment variable within `DatabaseSeeder`:

1. **Standard Seeding**:
   - Executes when `COMPREHENSIVE_SEEDING` is false or not set.
   - Provisions essential system requirements: super users, base roles, default currencies, and basic dummy data for products, customers, and warehouses.
   - Ideal for rapid local development or automated CI/CD testing environments where minimal data is preferred.

2. **Comprehensive Seeding**:
   - Executes when `COMPREHENSIVE_SEEDING=true`.
   - Utilizes realistic data factories (`ComprehensiveDataSeeder`, `SalesAndPurchasesSeeder`) to populate the system with a large, interconnected dataset simulating a live production environment.
   - Ideal for staging environments, load testing, or providing feature-rich demonstrations to stakeholders.

### Seeder Best Practices
- **Dependency Checks**: Seeders (e.g., `ProductsSeeder`) proactively verify if dependent records (like Warehouses or Categories) exist before insertion, dynamically creating them if absent to prevent foreign key constraint violations.
- **Performance Optimization**: For bulk insertions, seeders occasionally bypass Eloquent models in favor of the `DB::table()->insert()` query builder method. This prevents memory leaks and avoids triggering model observers or global scopes during massive data ingestion.
