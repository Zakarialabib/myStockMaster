# Livewire v4 Enhancements Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Standardize and optimize 161+ Livewire components using v4 features (Attributes, Island architecture, Morphing) while implementing a Trait + Alpine pattern for modals to avoid inheritance traps.

**Architecture:** 
- Alpine.js handles modal DOM visibility (`x-data="{ open: false }" @open-modal.window="..."`).
- Livewire components use a `ManagesModal` trait for data hydration, error resetting, and dispatching close events to Alpine.
- Use `#[Locked]` for secure IDs and `#[Url]` for shareable state.
- Strictly type all properties and fix specific correctness issues identified in the spec.

**Tech Stack:** Laravel, Livewire v4, Alpine.js, Tailwind CSS

---

### Task 1: Create the Modal Management Trait

**Files:**
- Create: `app/Livewire/Utils/ManagesModal.php`

- [ ] **Step 1: Write the minimal implementation**

```php
<?php

namespace App\Livewire\Utils;

trait ManagesModal
{
    /**
     * Reset the modal state, including validation errors and form objects.
     */
    public function resetModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        
        // If the component has a specific reset form method, call it.
        // Usually handled by the component's own lifecycle or #[On] listener,
        // but this provides a convenient hook.
        if (method_exists($this, 'resetForm')) {
            $this->resetForm();
        }
    }

    /**
     * Dispatch an event to Alpine.js to close the modal.
     */
    public function closeModal(string $modalId): void
    {
        $this->dispatch('close-modal', id: $modalId);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Utils/ManagesModal.php
git commit -m "feat(livewire): add ManagesModal trait for alpine modal integration"
```

### Task 2: Standardize Utils/HasDelete

**Files:**
- Modify: `app/Livewire/Utils/HasDelete.php`

- [ ] **Step 1: Update the trait to require explicit ability name**

```php
<?php

namespace App\Livewire\Utils;

use Illuminate\Support\Facades\Gate;

trait HasDelete
{
    /**
     * Components using this trait should define this property or override the method.
     * e.g., protected string $deleteAbility = 'product_delete';
     */
    protected function getDeleteAbility(): string
    {
        if (property_exists($this, 'deleteAbility')) {
            return $this->deleteAbility;
        }

        // Fallback or throw exception to enforce strictness
        throw new \Exception('Component using HasDelete must define $deleteAbility property or override getDeleteAbility().');
    }

    public function delete(int $id): void
    {
        Gate::authorize($this->getDeleteAbility());

        $model = $this->getModel()::findOrFail($id);
        $model->delete();

        $this->dispatch('success', __('Deleted successfully'));
        $this->dispatch('refreshTable');
    }
    
    // ... keep getModel() abstract or as-is ...
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Utils/HasDelete.php
git commit -m "refactor(livewire): require explicit delete ability in HasDelete trait"
```

### Task 3: Fix Settings Customizers Generic `updated` Hook

**Files:**
- Modify: `app/Livewire/Settings/AppCustomizer.php`
- Modify: `app/Livewire/Settings/TemplateCustomizer.php`

- [ ] **Step 1: Refactor AppCustomizer**

Change `public function updated($propertyName)` to specific property updates or use `updated()` with a switch statement to only run logic on specific properties. Given the generic nature, we will implement scoped methods if possible, or leave it as `updated($propertyName)` but ensure it checks the property before doing heavy lifting. 
*Note: We will apply `wire:model.blur` to the blade files, but for the component, we ensure it's not a generic `updated()` without args.*

```php
    // In AppCustomizer.php
    public function updated($property): void
    {
        // Only run specific logic if needed, otherwise rely on a 'save' action
        $this->validateOnly($property);
    }
    
    // Instead of auto-saving on every keystroke, ensure there's a save method
    public function save(): void
    {
        $this->validate();
        // save logic...
        $this->dispatch('success', 'Settings updated');
    }
```

- [ ] **Step 2: Refactor TemplateCustomizer similarly**

```php
    // In TemplateCustomizer.php
    public function updated($property): void
    {
        $this->validateOnly($property);
    }
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Settings/AppCustomizer.php app/Livewire/Settings/TemplateCustomizer.php
git commit -m "refactor(livewire): scope updated hooks in settings customizers"
```

### Task 4: Fix NotificationManager Computed Property

**Files:**
- Modify: `app/Livewire/Notifications/NotificationManager.php`

- [ ] **Step 1: Scope the lowQuantity computed property**

```php
use Livewire\Attributes\Computed;
use App\Models\Product;

// Inside NotificationManager.php
    #[Computed(persist: true)]
    public function lowQuantityCount(): int
    {
        // Only get the count, not the full collection, and persist it across the request
        return Product::whereColumn('quantity', '<=', 'alert_quantity')->count();
    }
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Notifications/NotificationManager.php
git commit -m "perf(livewire): scope lowQuantity to count and persist in NotificationManager"
```

### Task 5: Fix Products/Index Telegram Flow

**Files:**
- Modify: `app/Livewire/Products/Index.php`

- [ ] **Step 1: Fix `sendToTelegram` payload and typing**

```php
    // Change from: public function sendToTelegram(mixed $product)
    
    use Livewire\Attributes\On;
    
    #[On('product.send-telegram')]
    public function sendToTelegram(int $productId): void
    {
        $product = \App\Models\Product::findOrFail($productId);
        
        // logic to send telegram notification using the model
        $product->notify(new \App\Notifications\TelegramNotification()); // adjust to actual notification class used
        
        $this->dispatch('success', 'Telegram notification sent.');
    }
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Products/Index.php
git commit -m "fix(livewire): strictly type telegram flow in products index"
```

### Task 6: Apply `#[Locked]` and `#[Url]` to Products/Index

**Files:**
- Modify: `app/Livewire/Products/Index.php`

- [ ] **Step 1: Apply attributes to state**

```php
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;

// ...

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sortField = 'id';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    // If there is any sensitive state like warehouse context:
    // #[Locked]
    // public ?int $warehouse_id = null;
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Products/Index.php
git commit -m "refactor(livewire): apply Url and Locked attributes to Products Index"
```
