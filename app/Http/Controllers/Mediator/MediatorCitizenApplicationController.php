<?php

namespace App\Http\Controllers\Mediator;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Http\Requests\Mediator\MediatorCitizenApplication\FinishMediatorCitizenApplicationRequest;
use App\Http\Requests\Mediator\MediatorCitizenApplication\GetMediatorCitizenApplicationsRequest;
use App\Http\Requests\Mediator\MediatorCitizenApplication\UpdateMediatorCitizenApplicationStatusRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Jobs\ApplicationMediatorSelection;
use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorRejectionRepository;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorResultRepository;

class MediatorCitizenApplicationController
{
    public function __construct(
        private IApplicationRepository                  $applicationRepository,
        private IApplicationActivityLogRepository       $applicationActivityLogRepository,
        private IApplicationMediatorRejectionRepository $applicationMediatorRejectionRepository,
        private IApplicationMediatorResultRepository    $applicationMediatorResultRepository,
    )
    {

    }

    public function index(GetMediatorCitizenApplicationsRequest $request): PaginationResource
    {
        $applications = $this->applicationRepository->getMediatorApplicationsBy(auth()->id());

        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);
    }

    public function updateStatus(UpdateMediatorCitizenApplicationStatusRequest $request, Application $application): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findById($application->id);
        $validated = $request->validated();
        $type = null;

        if ($application->status !== ApplicationStatuses::PENDING->value) {
            return ErrorResource::make([
                'message' => trans('message.application-is-in-progress')
            ]);
        }

        $data = [];

        if ($validated['status'] === ApplicationStatuses::REJECTED->value || $validated['authorized_reject']) {
            $data['mediator_id'] = null;
            $type = ApplicationActivityLogTypes::REJECTED_BY_MEDIATOR->value;

            $this->applicationMediatorRejectionRepository->create([
                'application_id' => $application->id,
                'mediator_id' => auth()->id(),
                'reason' => !$validated['authorized_reject'] ? $validated['reason'] : null,
                'authorized_reject' => $validated['authorized_reject'],
            ]);
            ApplicationMediatorSelection::dispatch($application);
        }

        if ($validated['status'] === ApplicationStatuses::CONFIRMED->value) {
            $data['status'] = ApplicationStatuses::IN_PROGRESS->value;
            $type = ApplicationActivityLogTypes::CONFIRMED_BY_MEDIATOR->value;
        }
        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            $type,
            [
                'reason' => $validated['reason'] ?? null,
                'authorized_reject' => $validated['authorized_reject'],
            ]
        );

        $this->applicationRepository->update($application->id, $data);

        return SuccessResource::make([
            'message' => trans('message.application-updated')
        ]);
    }

    public function finish(FinishMediatorCitizenApplicationRequest $request, Application $application): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findById($application->id);
        $data = $request->validated();

        if ($application->status !== ApplicationStatuses::IN_PROGRESS->value) {
            return ErrorResource::make([
                'message' => trans('message.application-is-not-in-progress')
            ]);
        }

        $this->applicationRepository->update($application->id, [
            'status' => ApplicationStatuses::FINISHED->value
        ]);

        $this->applicationMediatorResultRepository->create([
            'application_id' => $application->id,
            'mediator_id' => auth()->id(),
            'status' => $data['status'],
            'message' => $data['message']
        ]);

        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            ApplicationActivityLogTypes::FINISHED_BY_MEDIATOR->value,
            $data
        );

        return SuccessResource::make([
            'message' => trans('message.application-updated')
        ]);
    }
}
