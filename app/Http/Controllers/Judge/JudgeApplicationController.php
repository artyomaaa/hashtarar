<?php

namespace App\Http\Controllers\Judge;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Http\Requests\Judge\Application\DestroyJudgeApplicationsRequest;
use App\Http\Requests\Judge\Application\GetJudgeApplicationsRequest;
use App\Http\Requests\Judge\Application\StoreJudgeApplicationRequest;
use App\Http\Requests\Judge\Application\UpdateJudgeApplicationsRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Jobs\ApplicationMediatorSelection;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Services\Application\ApplicationAttachmentService;
use App\Services\Application\ApplicationService;
use Illuminate\Support\Carbon;

class JudgeApplicationController
{
    public function __construct(
        private readonly IApplicationRepository $applicationRepository,
        private readonly ApplicationAttachmentService $applicationAttachmentService,
        private readonly ApplicationService $applicationService,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,

    )
    {

    }

    public function store(StoreJudgeApplicationRequest $request): SuccessResource
    {
        $data = $request->validated();
        $data['judge_id'] = auth()->id();
        $data['status'] = ApplicationStatuses::NEW->value;
        $application = $this->applicationRepository->create($data);

        if ($application) {
            $this->applicationAttachmentService->store($application, $data['attachments'], $data['demand_letter']);
            $this->applicationActivityLogRepository->log(
                $application->id,
                auth()->id(),
                ApplicationActivityLogTypes::CREATED->value,
            );
        }

        return SuccessResource::make([
            'data' => ApplicationResource::make($application),
            'message' => trans('message.application-created')
        ]);
    }

    public function index(GetJudgeApplicationsRequest $request): PaginationResource
    {
        $applications = $this->applicationRepository->getJudgeApplicationsBy(auth()->id());

        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);
    }

    public function destroy(DestroyJudgeApplicationsRequest $request, $id): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findOrFail($id);

        if ($application->status === ApplicationStatuses::NEW->value) {
            return ErrorResource::make([
                'message' => trans('message.you-can-not-delete-new-application')
            ]);
        }
        $this->applicationRepository->delete($application->id);
        return SuccessResource::make([
            'message' => trans('message.application-deleted')
        ]);
    }

    public function update(UpdateJudgeApplicationsRequest $request, int $id): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $addedAttachmentNames = [];
        $removedAttachmentNames = [];
        $application = $this->applicationRepository->findOrFail($id);
        if ($application->status !== ApplicationStatuses::REJECTED->value) {
            return ErrorResource::make([
                'message' => trans('message.you-can-not-edit-application')
            ]);
        }
        $application = $this->applicationRepository->updateAndGetUpdatedData('id', $id, [
            'case_type_id' => $data['case_type_id'],
            'status' => ApplicationStatuses::NEW->value,
        ]);
        if ($application) {

            if (isset($data['deleteDemandLetterId'])) {
                $this->applicationService->deleteApplicationFromStorage($application->application);
                $removedAttachmentNames[] = pathinfo($application->application, PATHINFO_BASENAME);
            }

            if (count($request->get('deletedAttachmentsIds', []))) {
                $removedAttachmentNames = array_merge($removedAttachmentNames, $this->applicationAttachmentService->deleteByIds($application->id, $request->deletedAttachmentsIds));
            }

            if (count($request->file('attachments', []))) {
                $addedAttachmentNames = $this->applicationAttachmentService->store($application, $data['attachments']);
            }

            if ($request->file('demand_letter')) {
                $addedAttachmentNames = array_merge($addedAttachmentNames, $this->applicationAttachmentService->store($application, [], $data['demand_letter']));
            }

            if (count($addedAttachmentNames)) {
                $this->applicationActivityLogRepository->log(
                    $application->id,
                    auth()->id(),
                    ApplicationActivityLogTypes::ATTACHMENTS_ADDED->value,
                    [
                        "filenames" => $addedAttachmentNames
                    ]
                );
            }

            if (count($removedAttachmentNames)) {
                $this->applicationActivityLogRepository->log(
                    $application->id,
                    auth()->id(),
                    ApplicationActivityLogTypes::ATTACHMENTS_REMOVED->value,
                    [
                        "filenames" => $removedAttachmentNames
                    ]
                );
            }

            $this->applicationActivityLogRepository->logWithOldData(
                $application->id,
                auth()->id(),
                ApplicationActivityLogTypes::UPDATED->value,
                ['case_type_id' => $data['case_type_id']],
                $application
            );

            return SuccessResource::make([
                'message' => trans('message.application-updated')
            ]);

        }

        return ErrorResource::make([
            'message' => trans('message.not-found')
        ]);
    }

}
