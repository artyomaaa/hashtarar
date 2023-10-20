<?php

namespace App\Listeners;

use App\Events\ApplicationUpcomingMeetingCreated;
use Illuminate\Support\Facades\Mail;

class SendApplicationUpcomingMeetingCreatedEmailNotification
{
    /**
     * Handle the event.
     */
    public function handle(ApplicationUpcomingMeetingCreated $event): void
    {

        Mail::send('emails.notifications.upcoming-meeting-created', $event->applicationUpcomingMeeting->toArray(), function ($message) use ($event) {
            $message->to($event->application->citizen->email);
            $message->subject('Ծանուցում');
        });
    }
}
