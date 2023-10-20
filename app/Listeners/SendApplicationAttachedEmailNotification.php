<?php

namespace App\Listeners;

use App\Events\ApplicationAttached;
use Illuminate\Support\Facades\Mail;


class SendApplicationAttachedEmailNotification
{


    /**
     * Handle the event.
     */
    public function handle(ApplicationAttached $event): void
    {

        Mail::send('emails.notifications.application-attached', $event->application->mediator->toArray(), function ($message) use ($event) {
            $message->to($event->application->citizen->email);
            $message->subject('Ծանուցում');
        });
    }
}
