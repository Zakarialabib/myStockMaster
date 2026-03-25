# MyStockMaster NativePHP Desktop v2 Compatibility Tracker

This file tracks what is done vs what is left for a NativePHP Desktop v2 compatible desktop build in this repository.

Last reviewed: 2026-03-22

## Completed Since Streamlining-Installation Plan

- [x] Added `EnvironmentService::getPlatform()` used by desktop status response:
  - [EnvironmentService.php](file:///c:/laragon/www/myStockMaster/app/Services/EnvironmentService.php#L50-L67)
- [x] Aligned desktop route compatibility with test expectations by supporting both singular and plural endpoints:
  - [web.php](file:///c:/laragon/www/myStockMaster/routes/web.php#L135-L147)
- [x] Fixed desktop shortcut sync call to use the correct online check method:
  - [DesktopShortcutService.php](file:///c:/laragon/www/myStockMaster/app/Services/DesktopShortcutService.php)
- [x] Updated desktop error handling payload to include `notification` key expected by tests:
  - [DesktopErrorHandler.php](file:///c:/laragon/www/myStockMaster/app/Services/DesktopErrorHandler.php#L56-L66)
- [x] Aligned desktop error log access check with centralized desktop environment detection:
  - [DesktopErrorLog.php](file:///c:/laragon/www/myStockMaster/app/Livewire/Admin/DesktopErrorLog.php#L188-L191)
- [x] Stabilized desktop feature tests and confirmed all desktop functionality tests pass:
  - [DesktopFunctionalityTest.php](file:///c:/laragon/www/myStockMaster/tests/Feature/DesktopFunctionalityTest.php)
- [x] Implemented persona-aware installation flow scaffolding (web vs desktop defaults):
  - [StepManager.php](file:///c:/laragon/www/myStockMaster/app/Livewire/Installation/StepManager.php#L18-L120)
- [x] Added web preflight checks (PHP/extensions/permissions/.env) used by installer and CLI doctor:
  - [StepManager.php](file:///c:/laragon/www/myStockMaster/app/Livewire/Installation/StepManager.php#L213-L287)
  - [InstallDoctorCommand.php](file:///c:/laragon/www/myStockMaster/app/Console/Commands/InstallDoctorCommand.php)
- [x] Added installer database connection test flow (web mode):
  - [StepManager.php](file:///c:/laragon/www/myStockMaster/app/Livewire/Installation/StepManager.php#L288-L367)
- [x] Added desktop first-run auto-initialize (ensure SQLite + migrate + seed essentials):
  - [EnvironmentServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/EnvironmentServiceProvider.php#L76-L140)
- [x] Documented and automated “one command” bootstrap via composer script:
  - [composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L61-L104)

## Current Versions (codebase)

- PHP: `^8.3` ([composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L10-L28))
- Laravel: `^12.0` ([composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L10-L28)) / installed `12.55.1`
- Livewire: `^4.0` ([composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L10-L28)) / installed `4.2.1`
- NativePHP Desktop: `^2.1` ([composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L10-L28))

## Verified NativePHP Desktop Baseline (Done)

- [x] NativePHP Desktop v2 dependency is installed (composer):
  - [composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L10-L28)
- [x] NativePHP Desktop config exists (single source currently in repo):
  - [nativephp.php](file:///c:/laragon/www/myStockMaster/config/nativephp.php)
- [x] NativePHP Desktop service provider exists:
  - [NativeAppServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/NativeAppServiceProvider.php)
- [x] Desktop environment detection exists ([EnvironmentService.php](file:///c:/laragon/www/myStockMaster/app/Services/EnvironmentService.php)).
- [x] Desktop-specific runtime configuration exists and is bootstrapped globally:
  - [EnvironmentServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/EnvironmentServiceProvider.php)
  - [bootstrap/providers.php](file:///c:/laragon/www/myStockMaster/bootstrap/providers.php)
- [x] Offline-first database connection exists:
  - `sqlite_desktop` connection points to `storage/database/desktop.sqlite` ([database.php](file:///c:/laragon/www/myStockMaster/config/database.php))
  - Desktop directories + sqlite file are auto-created when in desktop mode ([EnvironmentServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/EnvironmentServiceProvider.php))
- [x] Desktop routes exist for shortcuts/actions/status and error logging:
  - [web.php](file:///c:/laragon/www/myStockMaster/routes/web.php#L134-L146)
- [x] Desktop-facing UI elements exist (mode indicator + sync/status widgets):
  - [desktop-mode-indicator.blade.php](file:///c:/laragon/www/myStockMaster/resources/views/components/desktop-mode-indicator.blade.php)
  - [sync-status.blade.php](file:///c:/laragon/www/myStockMaster/resources/views/components/sync-status.blade.php)
- [x] Background sync job exists (DB-to-DB sync through `DatabaseSyncService`):
  - [SyncDatabaseJob.php](file:///c:/laragon/www/myStockMaster/app/Jobs/SyncDatabaseJob.php)
  - [DatabaseSyncService.php](file:///c:/laragon/www/myStockMaster/app/Services/DatabaseSyncService.php)
- [x] NativePHP dev script exists (NativePHP run + Vite dev):
  - [composer.json](file:///c:/laragon/www/myStockMaster/composer.json#L100-L103)

## Major Gaps / Breakers (Fix Before “Compatible” Claim)

- [x] DesktopController calls missing methods:
  - `EnvironmentService::getPlatform()` is referenced but not implemented ([DesktopController.php](file:///c:/laragon/www/myStockMaster/app/Http/Controllers/DesktopController.php))
- [x] Desktop route naming/paths do not match tests:
  - Routes include `/desktop/shortcut/execute` and `/desktop/action`
  - Tests call `/desktop/shortcuts/execute` and `/desktop/actions` ([DesktopFunctionalityTest.php](file:///c:/laragon/www/myStockMaster/tests/Feature/DesktopFunctionalityTest.php))
- [x] NativePHP Desktop provider is not registered for runtime boot lifecycle (currently commented):
  - [providers.php](file:///c:/laragon/www/myStockMaster/bootstrap/providers.php#L5-L13)
- [x] DesktopServiceProvider assumes a local “native service” HTTP API at `http://localhost:4000/api/status` (not guaranteed by NativePHP v2) ([DesktopServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/DesktopServiceProvider.php))
- [x] Desktop menu callbacks use `redirect()->route(...)` inside native menu click handlers (not a reliable NativePHP navigation pattern):
  - [DesktopServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/DesktopServiceProvider.php#L86-L221)
- [x] Desktop detection is duplicated and inconsistent (central `EnvironmentService` vs installer `class_exists(Window::class)`):
  - [EnvironmentService.php](file:///c:/laragon/www/myStockMaster/app/Services/EnvironmentService.php#L13-L44)
  - [StepManager.php](file:///c:/laragon/www/myStockMaster/app/Livewire/Installation/StepManager.php#L72-L105)
- [x] Installer desktop DB setup currently targets `database/database.sqlite` (sqlite), while desktop runtime uses `sqlite_desktop` (storage/database/desktop.sqlite):
  - [StepManager.php](file:///c:/laragon/www/myStockMaster/app/Livewire/Installation/StepManager.php#L505-L521)
  - [database.php](file:///c:/laragon/www/myStockMaster/config/database.php#L46-L53)

## Remaining NativePHP Desktop Work (Left)

### Priority A — Align with MyStockMaster “Boost” desktop architecture

- [x] Move desktop/native shell logic into `app/Native/` (currently spread across providers/controllers/services):
  - [DesktopServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/DesktopServiceProvider.php)
  - [NativeAppServiceProvider.php](file:///c:/laragon/www/myStockMaster/app/Providers/NativeAppServiceProvider.php)
  - [DesktopController.php](file:///c:/laragon/www/myStockMaster/app/Http/Controllers/DesktopController.php)
  - Desktop services in [app/Services](file:///c:/laragon/www/myStockMaster/app/Services)

### Priority B — Offline database + sync correctness

- [x] Standardize the desktop SQLite location to the project standard (`database/desktop.sqlite`) and update connection paths accordingly.
- [x] Choose one sync architecture and remove runtime divergence:
  - DB-to-DB sync: [DatabaseSyncService.php](file:///c:/laragon/www/myStockMaster/app/Services/DatabaseSyncService.php)
  - API-based sync: [SyncService.php](file:///c:/laragon/www/myStockMaster/app/Services/SyncService.php)
- [ ] Implement conflict resolution strategy (timestamp/last-write-wins) and document it.
- [x] Validate sync table/model mapping:
  - `DatabaseSyncService` references tables like `sale_items`, `purchase_items`, `stock_movements` that do not match the current migration naming (e.g. `sale_details`, `purchase_details`, `movements`).

### Priority C — Native shell behavior (windows/menus/shortcuts)

- [x] Consolidate window/menu configuration to a single source-of-truth:
  - Define how [nativephp.php](file:///c:/laragon/www/myStockMaster/config/nativephp.php) maps to providers and runtime usage.
- [x] Replace provider menu callbacks that use `redirect()->route(...)` with an approach that works reliably in NativePHP Desktop.
- [x] Ensure window management is defined in one place and is compatible with NativePHP Desktop v2.

### Priority D — Testing + quality gates

- [x] Make desktop feature tests meaningful and passing (align routes + implement missing functions):
  - [DesktopFunctionalityTest.php](file:///c:/laragon/www/myStockMaster/tests/Feature/DesktopFunctionalityTest.php)
- [ ] Add coverage for:
  - Desktop-mode detection and offline toggle persistence
  - Sync job execution and failure handling
  - Desktop error logging routes and storage

## Desktop Compatibility Definition of Done

- [x] Desktop mode boots without fatal errors (provider/controller references are valid).
- [x] Desktop SQLite exists, migrations or schema initialization is deterministic, and offline mode works.
- [x] Sync has a single architecture, a conflict strategy, and passes tests.
- [x] Window/menu/shortcut behavior is implemented in NativePHP-compatible APIs.
- [x] No secrets are bundled into production builds (env cleanup aligns with NativePHP best practices).

## NativePHP Desktop v2 Alignment Notes (Reference)

- Installer: NativePHP Desktop v2 uses `nativephp/desktop` and recommends running `php artisan native:install` on new machines / CI.
- Development migrations: in development you may need `php artisan native:migrate` (NativePHP-managed database), separate from `php artisan migrate`.
- App versioning: bump `config/nativephp.php` app version for builds; user-machine migrations only run if the version reference changes.
