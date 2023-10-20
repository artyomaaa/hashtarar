<?php

namespace App\Listeners;

use App\Events\ApplicationConfirmedOrRejected;
use Illuminate\Support\Facades\Mail;

class SendApplicationConfirmedOrRejectedEmailNotification
{

    /**
     * Handle the event.
     */
    public function handle(ApplicationConfirmedOrRejected $event): void
    {
        $email = $event->application->citizen ? $event->application->citizen->email : $event->application->judge->email;
        Mail::send('emails.notifications.application-confirmed-or-rejected', $event->application->toArray(), function ($message) use ($email, $event) {
            $message->to($email);
            $message->subject('Ծանուցում');
        });
    }
}
