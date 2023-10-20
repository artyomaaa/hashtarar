<?php

namespace App\Listeners;

use App\Events\ApplicationAttachedByJudge;
use Illuminate\Support\Facades\Mail;

class SendApplicationAttachedByJudgeEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApplicationAttachedByJudge $event): void
    {
        Mail::send('emails.notifications.application-attached-by-judge',[], function ($message) use ($event) {
            $message->to($event->application->mediator->email);
            $message->subject('Ծանուցում');
        });

    }
}
