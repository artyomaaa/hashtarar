<?php

namespace App\Http\Controllers\Application;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Events\ApplicationConfirmedOrRejected;
use App\Http\Requests\Application\DestroyApplicationRequest;
use App\Http\Requests\Application\DownloadApplicationRequest;
use App\Http\Requests\Application\GetApplicationRequest;
use App\Http\Requests\Application\GetApplicationsRequest;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationStatusRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Jobs\ApplicationMediatorSelection;
use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Services\Application\ApplicationAttachmentService;
use App\Services\Application\ApplicationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController
{
    public function __construct(
        private readonly IApplicationRepository            $applicationRepository,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
        private readonly ApplicationAttachmentService      $applicationAttachmentService,
        private readonly ApplicationService                $applicationService,
    )
    {

    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findById($application->id);
        $data = $request->validated();

        if ($application->status !== ApplicationStatuses::NEW->value) {
            return ErrorResource::make([
                'message' => trans('message.only-new-application-can-be-updated')
            ]);
        }

        $this->applicationRepository->update($application->id, $data);
        $type = null;

        if ($data['status'] === ApplicationStatuses::REJECTED->value) {
            $type = ApplicationActivityLogTypes::REJECTED_BY_EMPLOYEE->value;
        }

        //TODO Comments will be opened after the notification logic is integrated,because the are email limit
//        $updatedApplication = $this->applicationRepository->findById($application->id);
//        $updatedApplication->reason = $data['reason'] ?? null;
//        event(new ApplicationConfirmedOrRejected($updatedApplication));

        if ($data['status'] === ApplicationStatuses::CONFIRMED->value) {
            ApplicationMediatorSelection::dispatch($application);
            $type = ApplicationActivityLogTypes::CONFIRMED_BY_EMPLOYEE->value;
        }

        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            $type,
            [
                'reason' => $data['reason'] ?? null,
            ]
        );

        return SuccessResource::make([
            'message' => trans('message.application-updated')
        ]);
    }

    public function index(GetApplicationsRequest $request): PaginationResource
    {
        $applications = $this->applicationRepository->getApplications();

        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);
    }

    public function show(GetApplicationRequest $request, Application $application): SuccessResource
    {
        return SuccessResource::make([
            'data' => ApplicationResource::make(
                $this->applicationRepository->findById($application->id)
            )
        ]);
    }

    public function store(StoreApplicationRequest $request): SuccessResource
    {
        $data = $request->validated();
        $applicationModificationAllowedHours = config('app.application_modification_allowed_hours');
        $data['status'] = ApplicationStatuses::CONFIRMED->value;
        $application = $this->applicationRepository->create($data);

        if ($application) {
            $this->applicationAttachmentService->store($application, $data['attachments']);
            ApplicationMediatorSelection::dispatch($application)->delay(Carbon::now()->addHours($applicationModificationAllowedHours));
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

    public function update(UpdateApplicationRequest $request, Application $application): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $user = auth()->user();
        $addedAttachmentNames = [];
        $removedAttachmentNames = [];
        $data['status'] = $user->isEmployeeOrAdmin() ? ApplicationStatuses::CONFIRMED->value : ApplicationStatuses::NEW->value;
        $application = $this->applicationRepository->findById($application->id);

        if ($user->isCitizen() && $application->status !== ApplicationStatuses::REJECTED->value) {
            return ErrorResource::make([
                'message' => trans('message.only-rejected-application-can-be-updated')
            ]);
        }

        if ($user->isEmployeeOrAdmin() && in_array($application->status, [ApplicationStatuses::IN_PROGRESS->value, ApplicationStatuses::FINISHED->value])) {
            return ErrorResource::make([
                'message' => trans('message.application-is-in-progress')
            ]);
        }

        $this->applicationRepository->update($application->id, $data);
        if (isset($data['application'])) {
            $addedAttachmentNames[] = $this->applicationService->store($application, $data['application']);
        }

        if (isset($data['deletedApplicationId'])) {
            $this->applicationService->deleteApplicationFromStorage($application->application);
            $removedAttachmentNames[] = pathinfo($application->application, PATHINFO_BASENAME);
        }

        if (count($request->file('attachments', []))) {
            $addedAttachmentNames = array_merge($addedAttachmentNames, $this->applicationAttachmentService->store($application, $data['attachments']));
        }

        if (count($request->get('deletedAttachmentsIds', []))) {
            $removedAttachmentNames = array_merge($removedAttachmentNames, $this->applicationAttachmentService->deleteByIds($application->id, $request->deletedAttachmentsIds));
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

    public function destroy(DestroyApplicationRequest $request, Application $application): SuccessResource|ErrorResource
    {
        $application = $this->applicationRepository->findOrFail($application->id);

        if (in_array($application->status, [ApplicationStatuses::IN_PROGRESS->value, ApplicationStatuses::FINISHED->value])) {
            return ErrorResource::make([
                'message' => trans('message.application-is-in-progress')
            ]);
        }

        $this->applicationRepository->delete($application->id);

        return SuccessResource::make([
            'message' => trans('message.application-deleted')
        ]);
    }

    public function download(DownloadApplicationRequest $request, int $applicationId): ErrorResource|StreamedResponse
    {
        $application = $this->applicationRepository->findOrFail($applicationId);

        if (Storage::exists($application->application)) {
            return Storage::download($application->application);
        }

        return ErrorResource::make([
            'message' => trans('messages.file-not-found')
        ]);
    }
}
