<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Purchase;
use App\Models\Setting;
use App\Notifications\PurchaseNotification;
use Illuminate\Support\Facades\Notification;

class PurchaseObserver
{
    public function created(Purchase $purchase): void
    {
        $settings = Setting::first();
        $triggers = $settings?->notification_triggers ?? [];

        if (in_array('mail', $triggers['purchase_created'] ?? [])) {
            if ($purchase->supplier && $purchase->supplier->email) {
                Notification::route('mail', $purchase->supplier->email)
                    ->notify(new PurchaseNotification($purchase));
            }
        }
    }
}
