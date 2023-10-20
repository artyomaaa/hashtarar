<?php

namespace App\Http\Controllers\Mediator;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Http\Requests\Mediator\MediatorCitizenApplication\GetMediatorCitizenApplicationsRequest;
use App\Http\Requests\Mediator\MediatorCitizenApplication\UpdateMediatorJudgeApplicationStatusRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Jobs\ApplicationMediatorSelection;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorRejectionRepository;

class MediatorJudgeApplicationController
{
    public function __construct(
        private readonly IApplicationRepository                  $applicationRepository,
        private readonly IApplicationActivityLogRepository       $applicationActivityLogRepository,
        private readonly IApplicationMediatorRejectionRepository $applicationMediatorRejectionRepository,
    )
    {

    }

    public function index(GetMediatorCitizenApplicationsRequest $request): PaginationResource
    {
        $applications = $this->applicationRepository->getMediatorClaimLettersBy(auth()->id());

        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);
    }

    public function updateStatus(UpdateMediatorJudgeApplicationStatusRequest $request, int $applicationId): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findById($applicationId);
        $requestData = $request->validated();
        $requestData['authorized_reject'] =  $request->has('authorized_reject') ? $request->has('authorized_reject') : null;
        $type = null;

        if ($application->status !== ApplicationStatuses::PENDING->value) {
            return ErrorResource::make([
                'message' => trans('message.application-is-in-progress')
            ]);
        }
        $updatedData = [];
        if ($requestData['status'] === ApplicationStatuses::REJECTED->value) {
            $updatedData['mediator_id'] = null;
            $type = ApplicationActivityLogTypes::REJECTED_BY_MEDIATOR->value;

            $this->applicationMediatorRejectionRepository->create([
                'application_id' => $application->id,
                'mediator_id' => auth()->id(),
                'reason' => !$requestData['authorized_reject'] ? $requestData['reason'] : null,
                'authorized_reject' => $requestData['authorized_reject'],
            ]);
            ApplicationMediatorSelection::dispatch($application);
        }

        if ($requestData['status'] === ApplicationStatuses::CONFIRMED->value) {
            $updatedData['status'] = ApplicationStatuses::PENDING_JUDGE->value;
            $type = ApplicationActivityLogTypes::CONFIRMED_BY_MEDIATOR->value;
            $this->applicationRepository->update($application->id, $updatedData);
        }
        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            $type,
            [
                'reason' => $requestData['reason'] ?? null,
            ]
        );


        return SuccessResource::make([
            'message' => trans('message.application-updated')
        ]);

    }
}
