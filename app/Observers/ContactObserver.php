<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactNotification;

class ContactObserver
{
    /** Handle the Contact "created" event. */
    public function created(Contact $contact): void
    {
        $admin = User::whereHas('roles', static function ($query): void {
            $query->where('name', 'admin');
        })->first();

        if ($admin) {
            $admin->notify(new ContactNotification($contact));
        }
    }

    /** Handle the Contact "updated" event. */
    // public function updated(Contact $contact): void
    // {
    // }

    // /** Handle the Contact "deleted" event. */
    // public function deleted(Contact $contact): void
    // {
    // }

    // /** Handle the Contact "restored" event. */
    // public function restored(Contact $contact): void
    // {
    // }

    // /** Handle the Contact "force deleted" event. */
    // public function forceDeleted(Contact $contact): void
    // {
    // }
}
