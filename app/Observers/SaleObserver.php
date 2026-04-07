<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Sale;
use App\Models\Setting;
use App\Notifications\SaleNotification;
use Illuminate\Support\Facades\Notification;

class SaleObserver
{
    public function created(Sale $sale): void
    {
        $settings = Setting::first();
        $triggers = $settings?->notification_triggers ?? [];

        // Check if 'mail' is in the array of active channels for 'sale_created'
        if (in_array('mail', $triggers['sale_created'] ?? [])) {
            if ($sale->customer && $sale->customer->email) {
                Notification::route('mail', $sale->customer->email)
                    ->notify(new SaleNotification($sale));
            }
        }
    }
}
