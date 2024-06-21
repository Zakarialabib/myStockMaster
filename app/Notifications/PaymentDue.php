<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentDue extends Notification
{
    use Queueable;

    /**
     * The sale instance.
     *
     * @var Sale
     */
    public $sale;

    /**
     * Create a new notification instance.
     *
     * @param  Sale  $sale
     *
     * @return void
     */
    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $sale = $this->sale;

        if ( ! $sale->due_amount || ! $sale->payment_date) {
            $payment_date = Carbon::parse($sale->date)->addDays(15);

            if (now()->gt($payment_date)) {
                return [
                    'message' => __('Payment for sale with reference ').$sale->reference.__(' is due'),
                    'sale_id' => $sale->id,
                ];
            }
        }

        return [
            'message' => __('Payment for sale with reference ').$sale->reference.__(' is due on ').$sale->date,
            'sale_id' => $sale->id,
        ];
    }
}
