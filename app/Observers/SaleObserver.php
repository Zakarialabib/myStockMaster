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
        $activeChannels = $triggers['sale_created'] ?? [];

        if ($sale->customer) {
            if (in_array('mail', $activeChannels) && ! empty($sale->customer->email)) {
                Notification::route('mail', $sale->customer->email)
                    ->notify(new SaleNotification($sale, 'mail'));
            }

            if (in_array('whatsapp', $activeChannels) && ! empty($sale->customer->phone)) {
                Notification::route('whatsapp', $sale->customer->phone)
                    ->notify(new SaleNotification($sale, 'whatsapp'));
            }
        }
    }
}
