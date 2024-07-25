<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\OrderForms;
use App\Models\User;
use App\Notifications\OrderFormNotification;

class OrderFormObserver
{
    /** Handle the OrderForms "created" event. */
    public function created(OrderForms $orderForm): void
    {
        $admin = User::whereHas('roles', static function ($query): void {
            $query->where('name', 'admin');
        })->first();

        if ($admin) {
            $admin->notify(new OrderFormNotification($orderForm));
        }
    }

    /** Handle the OrderForms "updated" event. */
    public function updated(OrderForms $orderForm): void
    {
    }

    /** Handle the OrderForms "deleted" event. */
    public function deleted(OrderForms $orderForm): void
    {
    }

    /** Handle the OrderForms "restored" event. */
    public function restored(OrderForms $orderForm): void
    {
    }

    /** Handle the OrderForms "force deleted" event. */
    public function forceDeleted(OrderForms $orderForm): void
    {
    }
}
