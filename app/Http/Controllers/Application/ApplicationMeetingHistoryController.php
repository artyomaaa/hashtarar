<?php
declare(strict_types=1);

namespace App\Http\Controllers\Application;

use App\Enums\ApplicationActivityLogTypes;
use App\Http\Requests\Application\ApplicationMeetingHistory\DestroyApplicationMeetingHistoriesRequest;
use App\Http\Requests\Application\ApplicationMeetingHistory\GetApplicationMeetingHistoriesRequest;
use App\Http\Requests\Application\ApplicationMeetingHistory\StoreApplicationMeetingHistoriesRequest;
use App\Http\Requests\Application\ApplicationMeetingHistory\UpdateApplicationMeetingHistoriesRequest;
use App\Http\Resources\Application\ApplicationMeetingHistoryResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationMeetingHistoryRepository;
use App\Services\Application\ApplicationMeetingHistoryAttachmentService;
use Illuminate\Support\Arr;

class ApplicationMeetingHistoryController
{
    public function __construct(
        private readonly IApplicationMeetingHistoryRepository $applicationMeetingHistoryRepository,
        private readonly ApplicationMeetingHistoryAttachmentService $applicationMeetingHistoryAttachmentService,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
    )
    {

    }

    public function index(GetApplicationMeetingHistoriesRequest $request, Application $application): PaginationResource
    {
        $applicationMeetings = $this->applicationMeetingHistoryRepository->getMeetingHistoriesBy($application->id);

        return PaginationResource::make([
            'data' => ApplicationMeetingHistoryResource::collection($applicationMeetings->items()),
            'pagination' => $applicationMeetings
        ]);
    }

    public function store(StoreApplicationMeetingHistoriesRequest $request, int $applicationId): SuccessResource
    {
        $data = $request->validated();

        $meeting = $this->applicationMeetingHistoryRepository->create(
            $data + ['application_id' => $applicationId]
        );
        if ($meeting) {
            $this->applicationMeetingHistoryAttachmentService->store($meeting, $data['attachments']);
            $this->applicationActivityLogRepository->log(
                $applicationId,
                auth()->id(),
                ApplicationActivityLogTypes::MEDIATOR_CREATED_MEETING_HISTORY->value,
            );
        }
        return SuccessResource::make([
            'data' => ApplicationMeetingHistoryResource::make($meeting),
            'message' => trans('message.applications-meeting-histories-created-successful')
        ]);
    }

    public function update(UpdateApplicationMeetingHistoriesRequest $request, int $applicationId, int $meetingHistoriesId): SuccessResource
    {
        $data = $request->validated();
        $recording = $this->applicationMeetingHistoryRepository->findById($meetingHistoriesId, $applicationId);

        if ($recording) {
            $this->applicationMeetingHistoryRepository->update($meetingHistoriesId, $data);
            if (count($request->file('attachments', []))) {
                $this->applicationMeetingHistoryAttachmentService->store($recording, $data['attachments']);
            }
            if (count($request->get('deletedAttachmentsIds', []))) {
                $this->applicationMeetingHistoryAttachmentService->deleteByIds($recording->id, $request->input('deletedAttachmentsIds'));
            }
        }

        $this->applicationActivityLogRepository->logWithOldData(
            $applicationId,
            auth()->id(),
            ApplicationActivityLogTypes::MEDIATOR_UPDATED_MEETING_HISTORY->value,
            Arr::except($data, ['attachments', 'deletedAttachmentsIds']),
            $recording
        );

        return SuccessResource::make([
            'message' => trans('message.applications-meeting-histories-updated-successful')
        ]);
    }

    public function destroy(DestroyApplicationMeetingHistoriesRequest $request, int $applicationId, int $meetingHistoriesId): SuccessResource
    {
        $recording = $this->applicationMeetingHistoryRepository->findById($meetingHistoriesId, $applicationId);
        if ($recording) {
            $this->applicationMeetingHistoryRepository->delete($meetingHistoriesId);
        }

        return SuccessResource::make([
            'message' => trans('message.applications-meeting-histories-deleted-successful')
        ]);

    }
}
