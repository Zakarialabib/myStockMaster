# MyStockMaster Upgrade & Desktop Transformation Plan

## 1. Project Rules & Standards Update
- [x] Analyze `laravel-boost.md` guidelines.
- [ ] Update `project_rules.md` to reflect:
    - Laravel Boost standards (PHP 8.3+, strict types, constructor promotion).
    - Livewire v4 (target version).
    - NativePHP integration.
    - Offline-first architecture.

## 2. Dependency Upgrades
- [ ] **PHP**: Upgrade to 8.3.28+ (Ensure environment supports it).
- [ ] **Livewire**: Attempt upgrade to v4 (or latest v3.x if v4 is not yet stable/available).
- [ ] **NativePHP**: Ensure `nativephp/electron` is properly configured and up to date.

## 3. NativePHP Desktop Implementation
- [ ] **Configuration**: Review and update `config/nativephp.php`.
- [ ] **Window Management**: Define main window settings (width, height, resizable).
- [ ] **Menu Bar**: Custom menu implementation.
- [ ] **System Tray**: Add system tray support with quick actions.
- [ ] **Deep Linking**: Handle protocol handlers (e.g., `mystockmaster://`).

## 4. Offline-First & Database Sync Architecture
- [ ] **Local Database**: Ensure SQLite is used for the desktop version (`database/desktop.sqlite`).
- [ ] **Sync Mechanism**:
    - **Push**: Local changes -> Cloud (MySQL).
    - **Pull**: Cloud changes -> Local.
    - **Conflict Resolution**: Last-write-wins or manual resolution strategy.
- [ ] **Sync Job**: Create `SyncDatabaseJob` to handle data synchronization.
- [ ] **UI Indicator**: Add sync status indicator (Online/Offline/Syncing) in the navbar.

## 5. Codebase Refactoring (Boost Standards)
- [ ] **Strict Types**: Add `declare(strict_types=1);` to all PHP files.
- [ ] **Constructors**: Refactor to use PHP 8 constructor property promotion.
- [ ] **Clean Code**: Apply `laravel/pint` formatting with strict rules.

## 6. Deployment & Distribution
- [ ] **Build Process**: Script to build the desktop app (DMG, Exe, AppImage).
- [ ] **Auto-Updater**: Configure auto-update server or GitHub Releases integration.
