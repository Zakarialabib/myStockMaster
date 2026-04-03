# Controller & Route Improvement Plan (Laravel 12 Best Practices)

This plan outlines the steps required to modernize the application's routing and controller architecture to align with the latest Laravel 12 conventions and PHP 8.3+ features.

## 1. Adopt Route Attributes
Laravel 11/12 introduced and heavily supports PHP attributes for defining routes directly on controllers. This reduces the clutter in `routes/web.php` and `routes/api.php` by keeping the route definition adjacent to the logic.

- **Action:** Replace `Route::get()`, `Route::post()`, etc., in the route files with attributes like `#[Get('/...')]` and `#[Post('/...')]` on the controller methods.
- **Example:**
  ```php
  use Illuminate\Routing\Attributes\Get;

  class ExportController extends Controller
  {
      #[Get('/sales/pos/pdf/{id}', name: 'sales.pos.pdf')]
      public function salePos($id): Response
      {
          // ...
      }
  }
  ```
- **Note:** Livewire routes may still remain in `routes/web.php` unless Livewire components are also updated to support new attribute routing features if applicable.

## 2. Implement Invokable Controllers
Many controllers or methods currently handle single, distinct actions (e.g., exporting a specific PDF type, syncing data). Splitting these into single-action invokable controllers improves adherence to the Single Responsibility Principle.

- **Action:** Refactor controllers with only one primary responsibility into invokable controllers using the `__invoke()` method.
- **Targets:**
  - `ExportController` methods could be split into `ExportSalePosController`, `ExportPurchaseController`, etc., or kept grouped if they share significant logic, but for purely distinct actions, invokables are preferred.
  - `SendQuotationEmailController` should use `__invoke()`.

## 3. Replace Custom `BaseController` with API Resources
The current API controllers extend a `BaseController` to manually format JSON responses using `$this->sendResponse()` and `$this->sendError()`. This is an outdated pattern.

- **Action:** 
  - Remove `BaseController`.
  - Use native **Eloquent API Resources** (e.g., `ProductResource::collection($products)`) for successful responses.
  - Rely on Laravel's built-in exception handler for error formatting (or customize it in `bootstrap/app.php` using the new exception configuration).
- **Example:**
  ```php
  // Before
  return $this->sendResponse($products, 'Product List');
  
  // After
  return ProductResource::collection($products);
  ```

## 4. Extract Validation to Form Requests
Several API controllers use `try/catch` blocks and manual `$request->all()` assignments.

- **Action:** Move validation rules into dedicated `FormRequest` classes.
- **Benefit:** Controllers become thinner, only handling the happy path, while validation logic is encapsulated and reusable.

## 5. Strict Typing and PHP 8+ Features
Ensure the codebase fully leverages modern PHP features.

- **Action:**
  - Add strict return types (e.g., `: JsonResponse`, `: View`, `: RedirectResponse`) to all controller methods.
  - Use **Constructor Property Promotion** for injecting dependencies instead of traditional property assignments.
  - Remove redundant DocBlocks (e.g., `@param int $id`) when the type is already hinted in the method signature (`int $id`).

## 6. Middleware Modernization
Laravel 11/12 simplified middleware configuration.

- **Action:** Move middleware definitions from the `routes/` files to the controller's `middleware()` method (or apply them via Route Attributes like `#[Middleware('auth')]`) to keep access control logic co-located with the endpoint logic.
