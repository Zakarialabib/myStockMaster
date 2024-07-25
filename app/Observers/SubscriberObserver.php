<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Subscriber;
use App\Notifications\SubscribeNotification;
use App\Models\User;

class SubscriberObserver
{
    /** Handle the Subscriber "created" event. */
    public function created(Subscriber $subscriber): void
    {
        $admin = User::whereHas('roles', static function ($query): void {
            $query->where('name', 'admin');
        })->first();

        if ($admin) {
            $admin->notify(new SubscribeNotification($subscriber));
        }
    }

    /** Handle the Subscriber "updated" event. */
    public function updated(Subscriber $subscriber): void
    {
    }

    /** Handle the Subscriber "deleted" event. */
    public function deleted(Subscriber $subscriber): void
    {
    }

    /** Handle the Subscriber "restored" event. */
    public function restored(Subscriber $subscriber): void
    {
    }

    /** Handle the Subscriber "force deleted" event. */
    public function forceDeleted(Subscriber $subscriber): void
    {
    }
}
