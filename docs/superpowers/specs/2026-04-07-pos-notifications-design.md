# POS Optimizations & Smart Notifications Design

## 1. Overview
The goal of this phase is to optimize the Point of Sale (POS) checkout experience and make the notification routing smarter based on customer contact information. 
When a sale is completed, the system will intelligently route notifications to the customer via Email and/or WhatsApp if those fields exist. Additionally, the POS will introduce a "Post-Checkout Action" setting allowing admins to choose whether to display an A4 Invoice Preview, a Thermal Receipt Preview, or Auto-Print immediately. Finally, the Notification Logs UI will be themed to match the user's custom brand colors.

## 2. POS Checkout Optimizations

### 2.1 Post-Checkout Settings
A new configuration section under `Settings > System Config > POS` (or added to the `settings` table) will dictate what happens when a POS sale is finalized:
- **`pos_post_checkout_action`**: 
    - `preview_a4` (Show a modal with the A4 invoice preview and Print/Download buttons)
    - `preview_thermal` (Show a modal with an 80mm thermal receipt preview)
    - `auto_print_thermal` (Automatically trigger the browser's print dialog for a thermal receipt)

### 2.2 POS UI Implementation
- Upon successful sale creation in the Livewire POS component (`app/Livewire/Pos/Index.php`), instead of just clearing the cart, the system will read the `pos_post_checkout_action` setting.
- If a preview is selected, an Alpine.js/Livewire modal will open displaying an `<iframe>` or embedded HTML of the generated invoice/receipt.
- Print buttons inside the modal will trigger JavaScript `window.print()` scoped to the receipt content.

## 3. Smart Notification Routing

### 3.1 Contact Information Checks
The `SaleObserver` and `SalePaymentObserver` will be updated to be "smart".
Currently, they only check if `mail` is enabled in `notification_triggers`.
They will be refactored to:
1. Check the `notification_triggers` settings for the `sale_created` event.
2. If `mail` is enabled AND the customer has a valid `email` address, dispatch the `SaleNotification` via the `mail` channel.
3. If `whatsapp` is enabled AND the customer has a valid `phone` number, dispatch the notification via the `whatsapp` channel (assuming a WhatsApp integration exists or is planned; for now, we will log it to the database as a `whatsapp` channel attempt).
4. The system will send to BOTH channels simultaneously if the customer has both contact methods and both are toggled ON in the settings.

### 3.2 Dynamic Notification Logs Theming
The Notification Logs Data Table built in the previous phase will be updated to use the brand colors defined in `mail_styles['primary_color']`.
- Badges, pagination links, and active row highlights will dynamically pull from the CSS variable `--mail-primary` instead of static Tailwind classes (e.g., replacing `bg-indigo-600` with `style="background-color: var(--mail-primary)"`).

## 4. Database Changes
- Add `pos_post_checkout_action` (string, default: `preview_a4`) to the `settings` table.
