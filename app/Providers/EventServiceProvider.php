<?php

namespace App\Providers;

use App\Events\ApplicationAttachedByJudge;
use App\Events\ApplicationUpcomingMeetingCreated;
use App\Events\ApplicationAttached;
use App\Events\ApplicationConfirmedOrRejected;
use App\Listeners\SendApplicationAttachedByJudgeEmailNotification;
use App\Listeners\SendApplicationUpcomingMeetingCreatedEmailNotification;
use App\Listeners\SendApplicationAttachedEmailNotification;
use App\Listeners\SendApplicationConfirmedOrRejectedEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ApplicationUpcomingMeetingCreated::class => [
            SendApplicationUpcomingMeetingCreatedEmailNotification::class,
        ],
        ApplicationConfirmedOrRejected::class => [
            SendApplicationConfirmedOrRejectedEmailNotification::class,
        ],
        ApplicationAttached::class => [
            SendApplicationAttachedEmailNotification::class,
        ],
        ApplicationAttachedByJudge::class => [
            SendApplicationAttachedByJudgeEmailNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
