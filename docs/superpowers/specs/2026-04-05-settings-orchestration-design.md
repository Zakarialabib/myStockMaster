# UX/CX Settings Orchestration Strategy Implementation Spec

## Overview
Based on the `ux_cx_settings_strategy.md`, this specification outlines the execution of optimization patterns for the Settings, Roles, Permissions, and Language modules. The goal is to eliminate severe performance bottlenecks, reduce cognitive overload, and introduce robust layout standards across the administration sections of the application.

## Scope & Domains
1. **Language & Translations:**
   - `app/Livewire/Language/EditTranslation.php`
   - `resources/views/livewire/language/edit-translation.blade.php`
2. **Roles & Permissions:**
   - `app/Livewire/Role/Create.php` & `Edit.php`
   - `resources/views/livewire/role/create.blade.php` & `edit.blade.php`
3. **Settings Dashboard:**
   - `app/Livewire/Settings/Index.php`
   - `resources/views/livewire/settings/index.blade.php`
   - `app/Livewire/Forms/SettingsForm.php` (New)

## Core Modernizations

### Phase 1: Translation Performance Hotfix
**Problem:** Massive UI lag due to `wire:model.live` on potentially thousands of translation keys.
**Solution:**
- Convert `EditTranslation` to use `.defer` or standard `wire:model` bindings.
- Introduce array pagination (or chunking) to the UI.
- Implement a client-side or server-side search filter for keys.
- Add a sticky action bar with a clear "Save Translations" button.

### Phase 2: Role & Permission Categorization
**Problem:** Flat, overwhelming list of dozens/hundreds of permissions.
**Solution:**
- Refactor the Livewire `Create` and `Edit` components to group permissions by their prefix (e.g., `user_create`, `user_edit` grouped under `user`).
- Create a UI accordion or grid with group-level "Select All" Alpine.js toggles.
- Create `App\Livewire\Forms\RoleForm` to standardize the role creation state.

### Phase 3: Settings UX Consistency
**Problem:** Inconsistent saving behaviors, unorganized tabs, and lack of dirty-state tracking.
**Solution:**
- Centralize state into `App\Livewire\Forms\SettingsForm` covering all properties (company name, email, currency, etc.).
- Implement Alpine.js dirty state tracking (`x-data="{ isDirty: false }"`) that detects form changes and displays a warning if the user attempts to switch tabs without saving.
- Standardize the layout to match the split-pane or unified card UI used in other orchestrations.
- Ensure the `settings()` helper gracefully handles the refactored keys.

## E-Commerce Note
E-commerce settings (e.g., store toggle, theme settings) will be stubbed in the UI but marked as "Future Phase". The architecture will support a simple toggle flag (`is_ecommerce_active`) in the `settings` table to eventually expose the single-page e-commerce view.

## Implementation Steps
1. **Translations**: Remove `.live` bindings, implement search/pagination, and add sticky save bar.
2. **Roles**: Build the grouped permission grid and Form object.
3. **Settings**: Implement the `SettingsForm`, dirty-state tracking, and layout consistency.
4. **Validation**: Run tests and formatting.