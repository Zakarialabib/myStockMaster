# Livewire v4 Enhancements Design Spec

**Date**: 2026-04-27
**Status**: Approved
**Branch**: livewire-v4-enhancements

## 1. Executive Summary
This design specification outlines the standardization and optimization of the 161+ Livewire components in the application. The goal is to fully leverage Livewire v4 features (Island architecture, Form objects, Morphing, Attributes) while avoiding inheritance traps and reducing server round-trips.

## 2. Architecture & Patterns

### 2.1 Modal Management (Alpine + Traits)
To avoid Livewire inheritance traps (Base Components) and reduce unnecessary server calls for UI state:
- **UI State (Alpine.js)**: Modal visibility will be managed purely by Alpine.js. We will implement a global or reusable Alpine component/listener for modals (e.g., `x-data="{ open: false }" @open-modal.window="if ($event.detail.id === modalId) open = true"`).
- **Data Hydration (Livewire Trait)**: A new trait `App\Livewire\Utils\ManagesModal` will be created to handle backend operations:
  - Dispatches to Alpine to close/open (`$this->dispatch('close-modal', id: '...');`)
  - Provides a standardized `resetModal()` method to clear validation errors and reset Form objects.
- **Component Implementation**: Components explicitly use the trait and define their own `#[On('domain.action')]` listeners for hydration.

### 2.2 Event Contracts
- **Naming Convention**: `domain.action` (e.g., `product.deleted`, `cart.item-added`).
- **Payload Shape**: Strictly use named arguments. No mixed arrays or ambiguous IDs vs. Models.

### 2.3 State Security & URL Management
- **`#[Locked]`**: Applied to sensitive state that the client should never manipulate (e.g., `user_id`, `warehouse_id`, role gates).
- **`#[Url]`**: Applied to shareable state (filters, sort, search). Use `history: true` for significant page changes and `history: false` for minor tweaks.

### 2.4 Performance & DOM Stability
- **Input Churn**: Replace excessive `wire:model.live` on text inputs with `wire:model.blur` or `wire:model.live.debounce.300ms`.
- **Heavy Queries**: Move expensive queries from `render()` to `#[Computed(persist: true)]` where applicable, scoped to specific needs.
- **Morphing**: Wrap conditionals in persistent containers and enforce `wire:key` on looped items to prevent DOM re-indexing.

## 3. Correctness Fixes (Blockers)
Before applying the broad architectural patterns, the following specific issues will be resolved:
1. **Products/Index Telegram Flow**: Fix type mismatch (`mixed $product` vs ID) and standardize payload.
2. **Utils/HasDelete**: Remove dynamic gate string concatenation. Require explicit `$deleteAbility` definition to match policy names.
3. **Settings Customizers**: Replace generic `updated()` with specific `updatedXxx()` methods or use `wire:model.blur`.
4. **NotificationManager**: Scope the `lowQuantity()` computed property so it doesn't run a global query for every paginated request.

## 4. SPA Navigation
- Enforce `wire:navigate` on all internal links (sidebar/navbar).
- Use `->redirectRoute(..., navigate: true)` for programmatic Livewire redirects.

## 5. Type Strictness
- Eliminate `public mixed` properties.
- Strongly type all Livewire component properties to reduce serialization payload size and ensure correctness.
- Move complex state groupings (like bulk discounts) into dedicated Livewire Form objects.