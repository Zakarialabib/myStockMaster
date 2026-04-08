<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SalePayment;
use App\Models\Setting;
use App\Notifications\PaymentSaleNotification;
use Illuminate\Support\Facades\Notification;

class SalePaymentObserver
{
    public function created(SalePayment $payment): void
    {
        $settings = Setting::first();
        $triggers = $settings?->notification_triggers ?? [];
        $activeChannels = $triggers['payment_received'] ?? [];

        if ($payment->sale && $payment->sale->customer) {
            if (in_array('mail', $activeChannels) && ! empty($payment->sale->customer->email)) {
                Notification::route('mail', $payment->sale->customer->email)
                    ->notify(new PaymentSaleNotification($payment, 'mail'));
            }

            if (in_array('whatsapp', $activeChannels) && ! empty($payment->sale->customer->phone)) {
                Notification::route('whatsapp', $payment->sale->customer->phone)
                    ->notify(new PaymentSaleNotification($payment, 'whatsapp'));
            }
        }
    }
}
