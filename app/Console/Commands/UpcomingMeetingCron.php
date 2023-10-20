<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ApplicationActivityLogTypes;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationMeetingHistoryRepository;
use App\Repositories\Contracts\Application\IApplicationUpcomingMeetingRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpcomingMeetingCron extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upcomingMeeting:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private readonly IApplicationUpcomingMeetingRepository $applicationUpcomingMeetingRepository,
        private readonly IApplicationMeetingHistoryRepository $applicationMeetingHistoryRepository,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
    )
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $upcomingMeetings = $this->applicationUpcomingMeetingRepository->getUpcomingMeetingsByDateRange(
            Carbon::now()->subHour(),
            Carbon::now()
        );

        $insertedData = [];
        if (!empty($upcomingMeetings)) {
            foreach ($upcomingMeetings as $upcomingMeeting) {
                $currentDateTime = date('Y-m-d H:i:s');
                if ($currentDateTime > $upcomingMeeting['date']) {
                    $insertedData[] = [
                        'application_id' => $upcomingMeeting['application_id'],
                        'date' => $upcomingMeeting['date'],
                        'address' => $upcomingMeeting['address'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $this->applicationActivityLogRepository->log(
                        $upcomingMeeting['application_id'],
                        $upcomingMeeting->application->mediator_id,
                        ApplicationActivityLogTypes::MEDIATOR_CREATED_MEETING_HISTORY->value,
                    );
                }
            }
            $this->applicationMeetingHistoryRepository->insert($insertedData);
        }
    }
}
