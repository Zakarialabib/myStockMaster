# Refactor Mails to Notifications Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Convert existing `App\Mail` classes into `App\Notifications` that extend `BaseSystemNotification` so all system emails are natively logged to the `notifications` table.

**Architecture:** We will create new Notification classes (e.g., `SaleNotification`) that extend the `BaseSystemNotification` we built previously. We will move the Markdown/Mailable logic into the `toMail()` method of these Notifications. Then, we will update the Controllers/Jobs to use the `notify()` method instead of `Mail::to()->send()`. Finally, we will delete the old `App\Mail` classes.

**Tech Stack:** Laravel Notifications, Mailables.

---

### Task 1: Refactor Sale & Quotation Emails

**Files:**
- Create: `app/Notifications/SaleNotification.php`
- Create: `app/Notifications/QuotationNotification.php`
- Modify: `app/Http/Controllers/SendQuotationEmailController.php`
- Delete: `app/Mail/SaleMail.php`
- Delete: `app/Mail/QuotationMail.php`

- [ ] **Step 1: Create SaleNotification**

Create `app/Notifications/SaleNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Sale;
use Illuminate\Notifications\Messages\MailMessage;

class SaleNotification extends BaseSystemNotification
{
    public Sale $sale;

    public function __construct(Sale $sale, string $channelType = 'mail')
    {
        parent::__construct('Sale Details - ' . $sale->reference, $channelType);
        $this->sale = $sale;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.sale', ['sale' => $this->sale]);
    }
}
```

- [ ] **Step 2: Create QuotationNotification**

Create `app/Notifications/QuotationNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Notifications\Messages\MailMessage;

class QuotationNotification extends BaseSystemNotification
{
    public Quotation $quotation;

    public function __construct(Quotation $quotation, string $channelType = 'mail')
    {
        parent::__construct('Quotation Details - ' . $quotation->reference, $channelType);
        $this->quotation = $quotation;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.quotation', ['quotation' => $this->quotation]);
    }
}
```

- [ ] **Step 3: Update SendQuotationEmailController**

Modify `app/Http/Controllers/SendQuotationEmailController.php`:
Replace:
```php
use App\Mail\QuotationMail;
use Illuminate\Support\Facades\Mail;

// Inside invoke:
Mail::to($quotation->customer->email)->send(new QuotationMail($quotation));
```
With:
```php
use App\Notifications\QuotationNotification;
use Illuminate\Support\Facades\Notification;

// Inside invoke:
Notification::route('mail', $quotation->customer->email)
    ->notify(new QuotationNotification($quotation));
```

- [ ] **Step 4: Clean up old classes**

Run: `rm app/Mail/SaleMail.php app/Mail/QuotationMail.php`

- [ ] **Step 5: Commit**

```bash
git add app/Notifications/SaleNotification.php app/Notifications/QuotationNotification.php app/Http/Controllers/SendQuotationEmailController.php app/Mail/SaleMail.php app/Mail/QuotationMail.php
git commit -m "refactor(notifications): migrate sale and quotation mails to notifications"
```

---

### Task 2: Refactor Payment Emails

**Files:**
- Create: `app/Notifications/PaymentSaleNotification.php`
- Create: `app/Notifications/PaymentPurchaseNotification.php`
- Create: `app/Notifications/PaymentReturnNotification.php`
- Delete: `app/Mail/PaymentSaleMail.php`
- Delete: `app/Mail/PaymentPurchaseMail.php`
- Delete: `app/Mail/PaymentReturnMail.php`

- [ ] **Step 1: Create PaymentSaleNotification**

Create `app/Notifications/PaymentSaleNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SalePayment;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentSaleNotification extends BaseSystemNotification
{
    public SalePayment $payment;

    public function __construct(SalePayment $payment, string $channelType = 'mail')
    {
        parent::__construct('Payment Details - ' . $payment->reference, $channelType);
        $this->payment = $payment;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.payment.sale', ['payment' => $this->payment]);
    }
}
```

- [ ] **Step 2: Create PaymentPurchaseNotification**

Create `app/Notifications/PaymentPurchaseNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PurchasePayment;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentPurchaseNotification extends BaseSystemNotification
{
    public PurchasePayment $payment;

    public function __construct(PurchasePayment $payment, string $channelType = 'mail')
    {
        parent::__construct('Payment Details - ' . $payment->reference, $channelType);
        $this->payment = $payment;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.payment.purchase', ['payment' => $this->payment]);
    }
}
```

- [ ] **Step 3: Create PaymentReturnNotification**

Create `app/Notifications/PaymentReturnNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SaleReturnPayment;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReturnNotification extends BaseSystemNotification
{
    public SaleReturnPayment $payment;

    public function __construct(SaleReturnPayment $payment, string $channelType = 'mail')
    {
        parent::__construct('Payment Details - ' . $payment->reference, $channelType);
        $this->payment = $payment;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.payment.return', ['payment' => $this->payment]);
    }
}
```

- [ ] **Step 4: Clean up old classes**

Run: `rm app/Mail/PaymentSaleMail.php app/Mail/PaymentPurchaseMail.php app/Mail/PaymentReturnMail.php`

- [ ] **Step 5: Commit**

```bash
git add app/Notifications/PaymentSaleNotification.php app/Notifications/PaymentPurchaseNotification.php app/Notifications/PaymentReturnNotification.php app/Mail/PaymentSaleMail.php app/Mail/PaymentPurchaseMail.php app/Mail/PaymentReturnMail.php
git commit -m "refactor(notifications): migrate payment mails to notifications"
```

---

### Task 3: Refactor Purchase & Return Emails

**Files:**
- Create: `app/Notifications/PurchaseNotification.php`
- Create: `app/Notifications/ReturnSaleNotification.php`
- Create: `app/Notifications/ReturnPurchaseNotification.php`
- Delete: `app/Mail/PurchaseMail.php`
- Delete: `app/Mail/ReturnSaleMail.php`
- Delete: `app/Mail/ReturnPurchaseMail.php`

- [x] **Step 1: Create PurchaseNotification**

Create `app/Notifications/PurchaseNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Purchase;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseNotification extends BaseSystemNotification
{
    public Purchase $purchase;

    public function __construct(Purchase $purchase, string $channelType = 'mail')
    {
        parent::__construct('Purchase Details - ' . $purchase->reference, $channelType);
        $this->purchase = $purchase;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.purchase', ['purchase' => $this->purchase]);
    }
}
```

- [x] **Step 2: Create ReturnSaleNotification**

Create `app/Notifications/ReturnSaleNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SaleReturn;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnSaleNotification extends BaseSystemNotification
{
    public SaleReturn $saleReturn;

    public function __construct(SaleReturn $saleReturn, string $channelType = 'mail')
    {
        parent::__construct('Sale Return Details - ' . $saleReturn->reference, $channelType);
        $this->saleReturn = $saleReturn;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.sale-return', ['saleReturn' => $this->saleReturn]);
    }
}
```

- [x] **Step 3: Create ReturnPurchaseNotification**

Create `app/Notifications/ReturnPurchaseNotification.php`:
```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PurchaseReturn;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnPurchaseNotification extends BaseSystemNotification
{
    public PurchaseReturn $purchaseReturn;

    public function __construct(PurchaseReturn $purchaseReturn, string $channelType = 'mail')
    {
        parent::__construct('Purchase Return Details - ' . $purchaseReturn->reference, $channelType);
        $this->purchaseReturn = $purchaseReturn;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subjectTitle)
            ->markdown('emails.purchase-return', ['purchaseReturn' => $this->purchaseReturn]);
    }
}
```

- [x] **Step 4: Clean up old classes**

Run: `rm app/Mail/PurchaseMail.php app/Mail/ReturnSaleMail.php app/Mail/ReturnPurchaseMail.php`

- [x] **Step 5: Commit**

```bash
git add app/Notifications/PurchaseNotification.php app/Notifications/ReturnSaleNotification.php app/Notifications/ReturnPurchaseNotification.php app/Mail/PurchaseMail.php app/Mail/ReturnSaleMail.php app/Mail/ReturnPurchaseMail.php
git commit -m "refactor(notifications): migrate purchase and return mails to notifications"
```
