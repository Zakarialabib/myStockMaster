# NativePHP Desktop Architecture & Portability Plan

## 1. Summary & Objectives
The goal of this architecture plan is to elevate the existing Laravel/Livewire web application into a seamless, high-performance, and fully native desktop application using NativePHP v2. We must resolve cross-database incompatibilities (MySQL vs. SQLite), replace read-only `.env` configuration mechanisms with database-backed solutions, and establish a true Native OS Menu that communicates elegantly with our newly built Livewire v4 Single Page Application (SPA).

By executing this plan, the application will behave exactly like a native Windows application (distributed via `.exe` or installer) while maintaining its capability to synchronize data with a central remote MySQL server when online.

## 2. Current State Analysis
*   **Database Incompatibilities:** Several Action classes (`GenerateRevenueReportAction`, `CalculateCustomerMetricsAction`, `CalculateExpansionReadinessAction`, `GenerateProductAnalyticsAction`) contain hardcoded MySQL syntax (`DATE_FORMAT()`, `YEAR()`, `MONTH()`, `DATE()`). When the desktop app switches to its local `sqlite_desktop` database, these queries crash.
*   **Read-Only Configuration:** `MailConfigService` currently attempts to parse and write directly to the `.env` file to update SMTP settings. In a compiled NativePHP binary, the `.env` file is read-only, making these settings impossible to update.
*   **Menu & Navigation Architecture:** The current implementation attempts to use web redirects (`redirect()->route(...)`) inside the NativePHP menu definition (e.g., `DesktopServiceProvider.php`). This breaks the Livewire SPA experience, forces full browser reloads within the Electron window, and is generally unstable in NativePHP v2.
*   **Offline/Sync Model:** The app possesses a `DatabaseSyncService` that moves data between the local SQLite database and a remote MySQL server, currently relying on manual triggers.

## 3. Proposed Architecture & Execution Phases

### Phase 1: Database Portability & Abstraction (SQLite vs. MySQL)
1.  **Refactor Date Functions:** Update all analytical Action classes to utilize Laravel's query builder abstractions or the existing `db_date_format()` helper. We will replace `DATE_FORMAT`, `YEAR()`, and `MONTH()` with dynamic logic that detects the active database driver (`mysql` vs `sqlite`) and applies the correct syntax (`%Y-%m` vs `strftime('%Y-%m')`).
2.  **Refactor Subqueries:** Eliminate raw correlated subqueries inside aggregate functions (e.g., in `CalculateBreakEvenAction`) to ensure robust cross-platform execution.
3.  **Result:** The application can switch seamlessly between the online MySQL database and the offline SQLite database without crashing on reports or dashboards.

### Phase 2: Configuration Persistence (DB-Backed Settings)
1.  **Database Migration:** The `.env` file must be treated as immutable. We will ensure the `settings` table (which already exists for company info) is expanded to store critical technical configurations like SMTP credentials, Mail From Address, and external API keys.
2.  **Service Refactoring:** `MailConfigService` and the `Settings/Smtp.php` Livewire component will be rewritten to read/write from the `settings` database table instead of `file_put_contents('.env')`.
3.  **Boot Loading:** Create or update a Service Provider (e.g., `AppServiceProvider` or `SettingsServiceProvider`) to dynamically load these database settings into the Laravel `config()` repository at runtime (e.g., `config(['mail.mailers.smtp.host' => $settings->smtp_host])`), allowing Laravel's core mailer to function normally.

### Phase 3: Native OS Integration (Menu, Window, & SPA Events)
1.  **Native Window Configuration:** Update `NativeAppServiceProvider` to strictly define the initial window dimensions, min/max constraints, framing, and icon for the desktop build.
2.  **Native Menu Architecture:** Build a robust, native OS menu using `Native\Laravel\Facades\Menu`. This menu will include standard native entries (File, Edit, View, Window) alongside app-specific actions (Sync Data, Clear Cache).
3.  **SPA Event Communication:** Instead of the menu triggering web routes (which breaks the SPA), menu items will dispatch native events. We will implement a global Livewire listener (e.g., in `layouts/app.blade.php` or a dedicated invisible `NativeEventListener` component) that listens for these OS events and triggers `wire:navigate` or specific component actions internally.

### Phase 4: Sync Mechanics & Build Preparation
1.  **Manual Sync Flow:** Ensure the `DatabaseSyncService` is wired securely to a dedicated UI button or Native Menu item that triggers a loading state/modal, blocking the UI while the synchronization safely executes.
2.  **NativePHP Config:** Finalize `config/nativephp.php` with correct `app_id`, `version`, `author`, and verify the `cleanup_env_keys` array is sanitizing production secrets.

## 4. Verification Steps
*   Run the test suite specifically targeting the refactored analytical Action classes.
*   Manually switch the application to the `sqlite_desktop` connection and verify the dashboard and reports load successfully without syntax errors.
*   Verify SMTP settings save to the database and correctly override the configuration at runtime.
*   Launch the NativePHP dev environment (`php artisan native:serve`) and verify the OS menu dispatches events that correctly navigate the Livewire SPA.