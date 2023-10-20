<?php

declare(strict_types=1);

namespace App\Events;


use App\Models\Application;
use App\Models\ApplicationUpcomingMeeting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationUpcomingMeetingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Application                $application,
        public ApplicationUpcomingMeeting $applicationUpcomingMeeting,

    )
    {
    }

}
