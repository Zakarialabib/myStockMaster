<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderNotification;

class OrderObserver
{
    /** Handle the Order "created" event. */
    public function created(Order $order): void
    {
        // Notify the admin users
        $users = User::whereHas('roles', static function ($query): void {
            $query->where('name', 'admin');
        })->get();

        Notification::send($users, new NewOrderNotification($order));
    }

    /** Handle the Order "updated" event. */
    public function updated(Order $order): void
    {
        // if ($order->isDirty('status')) {
        //     $order->orderLogs()->create([
        //         'action'      => "updated",
        //         'attribute'   => "status",
        //         'old_value'   => $order->getOriginal('status'),
        //         'new_value'   => $order->status,
        //         'description' => "Order status changed from " . Str::upper($order->getOriginal('status')) . " to " . Str::upper($order->status),
        //     ]);
        // }
    }

    /** Handle the Order "deleted" event. */
    public function deleted(Order $order): void
    {
    }

    /** Handle the Order "restored" event. */
    public function restored(Order $order): void
    {
    }

    /** Handle the Order "force deleted" event. */
    public function forceDeleted(Order $order): void
    {
    }
}
