<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Events\ApplicationAttached;
use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Repositories\Contracts\Mediator\IApplicationMediatorSelectionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApplicationMediatorSelection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private Application $application
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        IApplicationMediatorSelectionRepository $mediatorSelectionRepository,
        IApplicationRepository                  $applicationRepository,
        IApplicationActivityLogRepository       $applicationActivityLogRepository,
    )
    {
        try {
            $mediator = $mediatorSelectionRepository->getRandomMediator($this->application);

            if ($mediator) {
                $applicationRepository->update($this->application->id, [
                    'status' => ApplicationStatuses::PENDING->value,
                    'mediator_id' => $mediator->user_id
                ]);

                $applicationActivityLogRepository->log(
                    $this->application->id,
                    $mediator->user_id,
                    ApplicationActivityLogTypes::MEDIATOR_SELECTED->value,
                );

                //TODO Comments will be opened after the notification logic is integrated,because the are email limit
//                $updatedApplication = $applicationRepository->findById($this->application->id);
//                event(new ApplicationAttached($updatedApplication));
            }
        } catch (\Exception $exception) {
            Log::error("Application mediator selection error::: " . $exception->getMessage());
        }
    }
}
