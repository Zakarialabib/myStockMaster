# Livewire v4 Mastery & Deep Architecture Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement bleeding-edge Livewire v4 features (Attributes, Teleport, Isolate, Session) to maximize native capabilities, eliminate custom JS/modals for simple actions, and protect component states, resulting in a zero-dependency, hyper-optimized SPA experience.

**Architecture:** 
- **Performance & Isolation:** Apply `#[Isolate]` to independent widgets (like `NotificationBell`) to prevent full-page DOM diffing. Use `#[Renderless]` on methods that don't need UI updates.
- **UX & State:** Utilize `#[Session]` for multi-step forms and `wire:dirty` to warn users of unsaved changes. Apply global `wire:offline` indicators.
- **Simplification:** Replace complex SweetAlert/JS delete modals with native `wire:confirm`. Move overlapping modals to the `<body>` using `@teleport`.
- **Security:** Use `#[Locked]` to strictly protect database IDs and sensitive properties from client-side tampering.

**Tech Stack:** Laravel 11/12, Livewire v3/v4 (Attributes, Directives)

---

### Task 1: Component Isolation & Renderless Actions

**Files:**
- Modify: `app/Livewire/Notifications/NotificationBell.php`

- [ ] **Step 1: Implement `#[Isolate]` and `#[Renderless]`**
Open `app/Livewire/Notifications/NotificationBell.php`.
Add `#[Isolate]` above the class declaration so the bell doesn't re-render when other components update.
Add `#[Renderless]` to `markAsRead()` and `markAllAsRead()` since they only dispatch events or update Alpine state and don't need a full HTML payload return.

```php
<?php
namespace App\Livewire\Notifications;

// ... other imports
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Renderless;

#[Isolate]
class NotificationBell extends Component
{
    // ...
    
    #[Renderless]
    public function markAsRead($notificationId)
    {
        // ... existing logic ...
        $this->dispatch('notificationUpdated');
    }

    #[Renderless]
    public function markAllAsRead()
    {
        // ... existing logic ...
        $this->dispatch('notificationUpdated');
    }
    // ...
}
```

- [ ] **Step 2: Commit**
```bash
git add app/Livewire/Notifications/NotificationBell.php
git commit -m "perf: isolate NotificationBell and use Renderless attributes for actions"
```

### Task 2: Secure Component State & Persistent UX

**Files:**
- Modify: `app/Livewire/Products/Edit.php`
- Modify: `resources/views/livewire/products/edit.blade.php`

- [ ] **Step 1: Secure properties and persist session**
Open `app/Livewire/Products/Edit.php`.
Protect the `Product` property from client tampering using `#[Locked]`.

```php
use Livewire\Attributes\Locked;

class Edit extends Component
{
    // ...
    #[Locked]
    public int $productId;
    
    public Product $product; // Keep this but ensure ID is locked
    
    public function mount($id): void
    {
        $this->productId = $id;
        $this->product = Product::findOrFail($id);
        // ...
    }
}
```

- [ ] **Step 2: Implement `wire:dirty` for unsaved changes**
Open `resources/views/livewire/products/edit.blade.php`.
Find the "Save Changes" button inside the `<form>`. Add `wire:dirty.class` to highlight it when the form has unsaved changes.

```html
<!-- Inside the submit button -->
<x-button type="submit" primary x-show="step === 3" wire:loading.attr="disabled" wire:dirty.class="bg-orange-500 hover:bg-orange-600">
    <i class="fas fa-save mr-2"></i> {{ __('Save Changes') }}
    <span wire:dirty class="ml-2 text-xs opacity-75">(Unsaved)</span>
</x-button>
```

- [ ] **Step 3: Commit**
```bash
git add app/Livewire/Products/Edit.php resources/views/livewire/products/edit.blade.php
git commit -m "feat: secure product ID with Locked and add dirty state UI"
```

### Task 3: Global Offline State & Teleportation

**Files:**
- Modify: `resources/views/layouts/app.blade.php`

- [ ] **Step 1: Add global offline indicator**
Open `resources/views/layouts/app.blade.php`. Just below the `<body>` tag, add the `wire:offline` directive.

```html
<body class="antialiased ...">
    <div wire:offline class="fixed top-0 left-0 w-full z-[100] bg-red-600 text-white text-center py-2 font-semibold shadow-md transition-all">
        <i class="fas fa-wifi-slash mr-2"></i> You are currently offline. Some features may be unavailable.
    </div>
    <!-- ... rest of body ... -->
```

- [ ] **Step 2: Add `@teleport` target**
At the very bottom of the `<body>` tag in `app.blade.php`, add a teleport target div. This ensures any complex Livewire modals can break out of overflow/z-index traps.

```html
    <!-- Footer -->
    <x-footer />
    
    <!-- Teleport Target for Modals -->
    <div id="modals-container"></div>
</body>
```

- [ ] **Step 3: Commit**
```bash
git add resources/views/layouts/app.blade.php
git commit -m "feat: add global wire:offline indicator and teleport container"
```

### Task 4: Simplify Deletions (Native `wire:confirm`)

**Files:**
- Modify: `resources/views/livewire/customers/index.blade.php` (and related `Index.php` if needed)

- [ ] **Step 1: Replace JS Modals with `wire:confirm`**
Open `resources/views/livewire/customers/index.blade.php`.
Find the delete button/link (currently dispatching `deleteModal`). Replace it with native `wire:confirm` to instantly delete without needing a separate modal component.

```html
<!-- Replace this: -->
<x-dropdown-link wire:click="dispatch('deleteModal', {{ $customer->id }})">
    {{ __('Delete') }}
</x-dropdown-link>

<!-- With this: -->
<x-dropdown-link wire:click="delete({{ $customer->id }})" wire:confirm="{{ __('Are you sure you want to delete this customer? This action cannot be undone.') }}">
    {{ __('Delete') }}
</x-dropdown-link>
```

- [ ] **Step 2: Ensure `delete` method exists in Component**
Verify `app/Livewire/Customers/Index.php` has a `delete($id)` method (it likely does, routing to the service). If not, implement it to call `CustomerService::delete($id)`.

- [ ] **Step 3: Commit**
```bash
git add resources/views/livewire/customers/index.blade.php
git commit -m "refactor: simplify delete action using native wire:confirm"
```