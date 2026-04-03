# Livewire Island Architecture Improvement Plan

This plan details the strategy for fully adopting Livewire v4 Island Architecture principles across the `app/Livewire` components. The primary goals are to reduce payload sizes, isolate component re-renders, improve perceived performance, and encapsulate complex state.

## 1. Implement `#[Isolate]` for Component Isolation
**Current State**: Most components, particularly complex nested ones or dashboards (e.g., `Dashboard/KpiCards.php`, `Dashboard/Transactions.php`), do not explicitly isolate their updates or use `#[Lazy(isolate: false)]`.
**Improvement**:
- Adopt the `#[Isolate]` attribute (or `#[Lazy(isolate: true)]`) on non-dependent components to ensure they do not trigger or wait for parent component updates.
- **Target Areas**: `Dashboard/KpiCards.php`, `Dashboard/Transactions.php`, `Sales/Recent.php`, and independent modals/widgets.
- **Benefit**: True island architecture prevents the "waterfall" effect of component updates, reducing unnecessary network requests and improving responsiveness.

## 2. Refactor to Form Objects
**Current State**: Large form components (e.g., `Sales/Create.php`, `Purchase/Create.php`, `Users/Edit.php`) define dozens of `public` properties and validation rules directly on the component class. Currently, only `LoginForm.php` utilizes Livewire's `Form` object pattern.
**Improvement**:
- Extract properties and validation logic from large components into dedicated `Livewire\Form` classes (e.g., `App\Livewire\Forms\SaleForm`, `App\Livewire\Forms\PurchaseForm`).
- **Target Areas**: All `Create.php` and `Edit.php` components across domains (Sales, Purchases, Users, Settings).
- **Benefit**: Cleans up the main component class, encapsulates validation logic, and reduces the risk of exposing sensitive data. It makes the component purely responsible for handling the view and high-level events.

## 3. Maximize Lazy Loading
**Current State**: Lazy loading is used in some dashboard components (`Dashboard.php`, `KpiCards.php`), but many data-heavy tables and non-critical UI elements load synchronously.
**Improvement**:
- Apply `#[Lazy]` to all complex datatables (`Sales/Index.php`, `Purchases/Index.php`), heavy report generation components (`Reports/*`), and off-screen modals.
- Create more granular placeholder views (e.g., skeleton loaders tailored to specific tables or forms) using the `placeholder()` method to improve the user experience during the initial load.
- **Benefit**: Drastically improves the Time to First Byte (TTFB) and perceived performance of the initial page load by rendering heavy components in a subsequent request.

## 4. Expand Use of Computed Properties
**Current State**: `#[Computed]` is well-utilized in the Dashboard and some analytics components, but other components still rely on manual data fetching in the `mount()` method or assigning Eloquent models to public properties.
**Improvement**:
- Audit components (e.g., `Sales/Create.php` fetching categories or customers) and replace public properties holding Eloquent collections with `#[Computed]` methods.
- Remove complex objects and collections from the component's public state to prevent them from being serialized/deserialized on every roundtrip.
- **Target Areas**: Dropdowns, lists of options (e.g., `Category::select('name', 'id')->get()` in `Sales/Create.php`), and read-only relationship data.
- **Benefit**: Reduces the payload size sent to the browser and lowers memory consumption on the server, as computed properties are evaluated on-demand and not serialized.

## 5. State Management Optimization
**Current State**: The `LivewireCartTrait` and other utility traits manage significant state arrays (`$cartContent`, `$quantity`, `$check_quantity`, etc.).
**Improvement**:
- Refactor traits into dedicated Action classes or Service classes where applicable, or encapsulate the cart state into a specific `CartForm` object to leverage Livewire's advanced state hydration/dehydration lifecycle.
- Use `wire:model.blur` or `wire:model.live.debounce` aggressively on text inputs within the Datatables and Forms to prevent excessive network requests during typing.

## Implementation Roadmap
1. **Phase 1**: Audit and implement `#[Isolate]` on all independent widgets and dashboard components.
2. **Phase 2**: Refactor the top 5 largest form components (`Sales`, `Purchases`, `Quotations`, `Returns`) into Livewire Form Objects.
3. **Phase 3**: Transition Eloquent collections in public properties to `#[Computed]` properties across all `Create/Edit` components.
4. **Phase 4**: Apply `#[Lazy]` loading to all `Index` datatables and Reports components.