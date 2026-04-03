# Dependencies & Relationships

MyStockMaster is a fully-featured application built on Laravel v12, utilizing PHP 8.3 and modern frontend tooling. The core architecture uses Livewire v4 for real-time reactivity without the need for extensive JavaScript frameworks like Vue or React, although Alpine.js is integrated.

## Core Backend Dependencies

- **`php`**: `^8.3` - Utilizing the latest PHP features (typed properties, readonly classes, enum enhancements).
- **`laravel/framework`**: `^12.0` - The foundational MVC framework.
- **`livewire/livewire`**: `^4.0` - The primary engine for building reactive UI components directly in PHP.
- **`nativephp/desktop`**: `^2.1` - Allows packaging the Laravel application into a native desktop app (Electron/Tauri) with local SQLite databases.
- **`laravel/reverb`**: `^1.5` - Native WebSocket server for real-time broadcasting and notifications.
- **`spatie/laravel-permission`**: `^6.3` - Handles complex Role-Based Access Control (RBAC).
- **`maatwebsite/excel`**: `^3.1.68` - For generating Excel and CSV exports for sales, purchases, and reporting.
- **`laravel-notification-channels/telegram`**: `^6.0` - Powers stock alerts and notifications directly to Telegram.
- **`doctrine/dbal`**: `^4.4` - Database abstraction layer for schema operations.
- **`simplesoftwareio/simple-qrcode`**: `^4.2` - Used for generating barcodes and QRCodes for products and receipts.

## Core Frontend Dependencies

- **`vite`**: `^7.0.7` - The primary build tool and development server.
- **`tailwindcss`**: `^4.1.11` - A utility-first CSS framework for rapid UI development.
- **`alpinejs`**: `^3.5.0` - A rugged, minimal framework for composing JavaScript behavior directly in your markup, serving as a companion to Livewire.
- **`sweetalert2`**: `^11.26.24` - Used globally for beautiful, responsive popup alerts and confirmations.
- **`apexcharts`**: `^3.44.0` - Renders complex analytics charts, KPIs, and revenue reports on the dashboard.
- **`vitepress`**: `^1.6.4` - A Vue-powered static site generator used to compile this documentation.

## Testing Dependencies

- **`pestphp/pest`**: `^3.8` - An elegant PHP testing framework with a focus on simplicity.
- **`laravel/dusk`**: `^8.3` - Expressive, easy-to-use browser automation and testing API.
- **`larastan/larastan`**: `^3.6` - Adds static analysis to the project, improving code quality.
- **`laravel/pint`**: `^1.29` - An opinionated PHP code style fixer for minimalists.
