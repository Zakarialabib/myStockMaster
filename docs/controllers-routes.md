# Controllers & Routes Documentation

## Overview
This document outlines the routing architecture, the distinction between API and Web layers, and the middleware usage in the application. The system primarily leverages Laravel's routing features alongside Livewire for the frontend, and provides traditional REST endpoints for the API.

## Routing Architecture
The application uses two main route files:
- **`routes/web.php`**: Handles browser-based requests, including authentication, admin dashboards, and Livewire components.
- **`routes/api.php`**: Handles stateless API requests for integrations and mobile/desktop clients.

The Web routing heavily utilizes [Livewire](https://livewire.laravel.com/) for interactive UI components, meaning many routes point directly to Livewire classes (e.g., `App\Livewire\Dashboard::class`) rather than traditional controllers. Traditional controllers are mostly reserved for tasks like exporting PDFs (`ExportController`) or specific system integrations.

## API vs Web

### Web Layer
- **Controllers**: Found directly in `app/Http/Controllers/`. They typically return views, handle file downloads, or process specific form actions not handled by Livewire.
- **Frontend**: Primarily driven by Livewire components. The `routes/web.php` maps URLs directly to these components (e.g., `Route::livewire('/customers', CustomersIndex::class)`).
- **Session State**: Uses Laravel's session-based authentication.

### API Layer
- **Controllers**: Located in `app/Http/Controllers/Api/`. They extend a custom `BaseController` which provides utility methods like `sendResponse()` and `sendError()` to ensure consistent JSON formatting.
- **Endpoints**: Defined using `Route::apiResource()` for standard CRUD operations on entities such as Products, Customers, Users, etc.
- **Authentication**: Stateless authentication using tokens (likely Sanctum), with explicit login/register endpoints.

## Middleware Usage

### Web Middleware
The web routes utilize a combination of middleware to secure the application:
- `auth`: Ensures the user is authenticated.
- `auth.session`: Manages the user's session state.
- `role:admin`: A custom middleware (likely provided by Spatie Permission) that restricts access to users with the 'admin' role. Most dashboard and management routes are protected under this middleware group.

### API Middleware
The API routes are automatically assigned the `api` middleware group by the framework. Additional middleware such as `auth:sanctum` or role-based checks can be applied directly to the endpoints or inside the controllers' constructors.

## Specific Route Groups
- **Admin**: Grouped under the `/admin` prefix, protected by `['auth', 'auth.session', 'role:admin']`. Contains all business logic interfaces (Sales, Purchases, Inventory).
- **Desktop/Native**: Special routes prefixed with `/desktop` for integration with NativePHP desktop applications, handling shortcuts, system status, and error logging.
- **Sync**: API endpoints under `/sync` for database synchronization (pull/push) between local and remote environments.
