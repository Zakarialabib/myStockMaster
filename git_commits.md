# Git Commit Strategy (Consolidated)

Skip desktop features. Max 15 files per commit.

---

## Commit 21 — Notifications & Printing
`refactor(notifications): upgrade notification manager and printing`
- `app/Livewire/Notifications/NotificationManager.php`
- `app/Livewire/Printer/Create.php`
- `app/Livewire/Printer/Index.php`
- `app/Jobs/PrintReceiptJob.php`
- `resources/views/livewire/notifications/notification-bell.blade.php`
- `resources/views/livewire/notifications/notification-manager.blade.php`
- `resources/views/livewire/backup/index.blade.php`
- `resources/views/pdf/*.blade.php`

## Commit 22 — Installation & Miscellaneous
`refactor(install): upgrade installation wizard and app-level config`
- `app/Livewire/Installation/PendingApproval.php`
- `app/Livewire/Admin/DatabaseSync.php`
- `resources/views/livewire/installation/`
- `resources/views/livewire/admin/database-sync.blade.php`
- `resources/views/livewire/utils/system-logs.blade.php`
- `app/Helpers/helpers.php`
- `app/Http/Controllers/HomeController.php`
- `routes/web.php`
- `composer.json`
- `phpstan.neon`
- `phpunit.xml`

## Commit 23 — Tests
`test: align feature tests with Livewire v4 upgrades`
- `tests/Browser/Pages/HomePage.php`
- `tests/Feature/CartServiceTest.php`
- `tests/Feature/DashboardTest.php`
- `tests/Feature/InstallationConfigTest.php`
- `tests/Feature/ProfileTest.php`

---

## SKIPPED — Desktop Features
- `app/Console/Commands/InitializeDesktopDatabase.php`
- `app/Console/Commands/SyncDesktopDatabase.php`
- `app/Jobs/SyncDatabaseJob.php`
- `app/Http/Controllers/Api/SyncController.php`
- `app/Http/Controllers/DesktopController.php`
- `app/Services/DatabaseSyncService.php`
- `app/Services/DesktopErrorHandler.php`
- `app/Services/DesktopShortcutService.php`
- `app/Services/EnvironmentService.php`
- `app/Services/SyncService.php`
- `app/Providers/DesktopServiceProvider.php`
- `app/Providers/EnvironmentServiceProvider.php`
- `app/Providers/NativeAppServiceProvider.php`
- `app/Livewire/Admin/DesktopErrorLog.php`
- `resources/views/livewire/admin/desktop-error-log.blade.php`
- `resources/lang/en/desktop.php`
- `resources/views/components/desktop-mode-indicator.blade.php`
- `resources/views/components/desktop-notification.blade.php`
- `resources/views/components/sync-status.blade.php`
- `tests/Feature/DesktopFunctionalityTest.php`
- `NATIVEPHP_TRANSFORMATION_PLAN.md`
- `tailwind.config.js`
- `upgrade_plan.md`
- `boost.json`
- `livewire_upgrade.md`
- `livewire_v4.md`
- `new.html`
