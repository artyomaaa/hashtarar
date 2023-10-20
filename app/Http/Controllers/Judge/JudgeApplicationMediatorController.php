<?php

declare(strict_types=1);

namespace App\Http\Controllers\Judge;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Judge\ApplicationMediator\GetJudgeApplicationMediatorRequest;
use App\Http\Requests\Judge\ApplicationMediator\MediatorApplicationJudgeAcceptOrRejectRequest;
use App\Http\Requests\Judge\ApplicationMediator\StoreJudgeApplicationMediatorRequest;
use App\Http\Requests\Judge\ApplicationMediator\UpdatedMediatorApplicationJudgeRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Jobs\ApplicationMediatorSelection;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Repositories\Contracts\ApplicationMediator\IApplicationMediatorRejectionRepository;
use App\Services\Application\ApplicationAttachmentService;
use App\Services\Application\ApplicationService;
use App\Services\Application\JudgeMediatorApplicationAttachmentService;

final class JudgeApplicationMediatorController extends Controller
{
    public function __construct
    (
        private readonly IApplicationRepository $applicationRepository,
        private readonly ApplicationAttachmentService $applicationAttachmentService,
        private readonly ApplicationService $applicationService,
        private readonly IApplicationMediatorRejectionRepository $applicationMediatorRejectionRepository,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
        private readonly JudgeMediatorApplicationAttachmentService $judgeMediatorApplicationAttachmentService,
    )
    {
    }

    public function index(GetJudgeApplicationMediatorRequest $request): PaginationResource
    {

        $applications = $this->applicationRepository->getJudgeApplicationsBy(auth()->id());
        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);

    }

    public function store(StoreJudgeApplicationMediatorRequest $request): SuccessResource
    {
        $data = $request->validated();
        $data['judge_id'] = auth()->id();
        $data['status'] = ApplicationStatuses::NEW->value;

        $application = $this->applicationRepository->create($data);

        if ($application) {
            $this->applicationService->store($application, $data['application']);
            $this->applicationAttachmentService->store($application, $data['attachments']);
        }

        return SuccessResource::make([
            'data' => ApplicationResource::make($application),
            'message' => trans('message.application-created')
        ]);
    }

    public function updateStatus(UpdatedMediatorApplicationJudgeRequest $request, int $applicationId): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findById($applicationId);
        $data = $request->validated();

        $type = null;
        if ($application->status === ApplicationStatuses::PENDING_JUDGE->value) {
            return ErrorResource::make([
                'message' => trans('message.application-is-in-progress')
            ]);
        }

        $this->applicationRepository->update($application->id, $data);

        if ($data['status'] === ApplicationStatuses::REJECTED->value) {
            $data['status'] = ApplicationStatuses::NEW->value;
            $data['authorized_reject'] = $request->has('authorized_reject') ? $request->has('authorized_reject') : null;
            $data['mediator_id'] = null;
            $type = ApplicationActivityLogTypes::REJECTED_BY_EMPLOYEE->value;

            $this->applicationMediatorRejectionRepository->create([
                'application_id' => $application->id,
                'mediator_id' => auth()->id(),
                'reason' => !$data['authorized_reject'] ? $data['reason'] : null,
                'authorized_reject' => $data['authorized_reject'],
            ]);
        }


        if ($data['status'] === ApplicationStatuses::CONFIRMED->value) {
            $type = ApplicationActivityLogTypes::CONFIRMED_BY_EMPLOYEE->value;
            ApplicationMediatorSelection::dispatch($application);
        }

        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            $type,
            [
                'reason' => $requestData['reason'] ?? null,
            ]
        );


        //TODO Comments will be opened after the notification logic is integrated,because the are email limit
//        event(new ApplicationAttachedByJudge($updatedApplication));

        return SuccessResource::make([
            'message' => trans('message.application-updated')
        ]);

    }

    public function mediatorApplicationAcceptOrReject(MediatorApplicationJudgeAcceptOrRejectRequest $request, int $applicationId): SuccessResource
    {
        $data = $request->validated();

        $application = $this->applicationRepository->findById($applicationId);
        $type = null;


        if ($data['status'] === ApplicationStatuses::REJECTED->value) {

            $data['authorized_reject'] = $request->has('authorized_reject') ? $request->has('authorized_reject') : null;
            $data['mediator_id'] = null;
            $type = ApplicationActivityLogTypes::REJECTED_BY_JUDGE->value;


            $this->applicationMediatorRejectionRepository->create([
                'application_id' => $application->id,
                'mediator_id' => $application->mediator->id,
                'authorized_reject' => $data['authorized_reject'],
            ]);
            ApplicationMediatorSelection::dispatch($application);
        }


        if ($data['status'] === ApplicationStatuses::CONFIRMED->value) {
            $data['status'] = ApplicationStatuses::IN_PROGRESS->value;
            $type = ApplicationActivityLogTypes::CONFIRMED_BY_JUDGE->value;
            $this->applicationRepository->updateAndGetUpdatedData('id', $application->id, ['status' => $data['status']]);
        }
        $this->judgeMediatorApplicationAttachmentService->store($application, $data['document']);

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
