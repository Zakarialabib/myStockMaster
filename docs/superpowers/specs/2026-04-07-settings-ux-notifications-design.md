# Settings UX & Unified Notifications Hub Design

## 1. Overview
The current settings page is a single, overwhelming view containing all application configurations. Many fields lack contextual explanations, leading to user confusion about where data goes and how it is protected. Additionally, the system lacks a unified way to track, manage, and template notifications (emails, WhatsApp, Telegram). 

This design document outlines a comprehensive UX overhaul of the Settings page using a **Nested Sidebar** layout and a **Dynamic Info Panel**, alongside the creation of a **Unified Notifications Hub** leveraging Laravel's native `notifications` system.

## 2. Architecture & Layout Redesign (Phase 1)

### 2.1 Navigation Structure
Transition from the current vertical tabs to a **Nested Sidebar** component. This allows deeper categorization without cluttering the screen.
*   `Settings`
    *   `General` (Company Info, Site Config, Appearance)
    *   `System` (Currency, Languages, Backup)
    *   `Invoicing` (Prefixes, Invoice Control)
    *   `Notifications` (Channels, Triggers, Templates, Logs)

### 2.2 Page Layout (3-Column Design)
1.  **Left Column:** Nested Sidebar for navigation.
2.  **Center Column:** The actual Livewire Form content (Inputs, Toggles, Selects).
3.  **Right Column (Dynamic Info Panel):** A context-aware panel.

### 2.3 UX Flow (Dynamic Explanations)
*   As the user clicks or focuses on any input field (e.g., `company_name`), Alpine.js dispatches an event to update the **Dynamic Info Panel**.
*   The panel will display:
    1.  **Purpose:** What this field does.
    2.  **Usage:** Where the data appears in the ERP (e.g., "Printed on all PDF invoices and reports").
    3.  **Privacy/Security:** Whether the data is public, internal, or encrypted (e.g., "SMTP Passwords are encrypted in the database").

## 3. The Unified Notification Hub (Phase 2)

### 3.1 Database & Core Architecture
Instead of creating new tables, we will use Laravel's native `notifications` table and `Notifiable` trait.
*   **Zero New Tables:** All notification history (Mail, Telegram, WhatsApp) will be logged here.
*   **Unified Logging:** We will configure our Notification classes to include the `'database'` channel in their `via()` method alongside the delivery channel.
*   **Data Storage:** The `toDatabase()` method on Notification classes will store metadata in the `data` JSON column, including:
    *   Subject / Title
    *   Channel used (`mail`, `whatsapp`, `telegram`)
    *   Status (`sent`, `failed`)
    *   Recipient summary

### 3.2 Configuration Storage
A new `notification_triggers` JSON column (or array cast) in the `settings` table to store event toggle states. Example: `['sale_created' => ['mail', 'database'], 'payment_received' => ['telegram', 'database']]`.

### 3.3 Hub Components (Nested under Settings > Notifications)
1.  **Delivery Channels:** A consolidated form for SMTP, Telegram Bot, and WhatsApp credentials.
2.  **Event Triggers:** A dashboard of toggle switches allowing admins to turn specific notifications on/off globally or per-channel.
3.  **Visual Templates:** A Livewire editor for modifying the HTML/Blade structure of system emails, providing a live preview similar to the invoice customizer.
4.  **Notification Logs:** A data table querying the `notifications` table, displaying the history of all dispatched messages, filterable by channel type and `notifiable_type`.

## 4. Implementation Constraints
*   Ensure all Livewire components are broken down properly to prevent a monolithic Settings component.
*   Maintain the sticky "Save Changes" button behavior from the current design.
*   Ensure Alpine.js is used for the Dynamic Info Panel to keep it snappy without round-trips to the server.
