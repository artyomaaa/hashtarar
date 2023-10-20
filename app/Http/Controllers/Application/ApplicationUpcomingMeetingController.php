<?php

namespace App\Http\Controllers\Application;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationMeetingStatuses;
use App\Enums\ApplicationStatuses;
use App\Events\ApplicationUpcomingMeetingCreated;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\DestroyApplicationUpcomingMeetingRequest;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\GetAllApplicationUpcomingMeetingsRequest;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\GetApplicationUpcomingMeetingsRequest;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\MediatorFreeHoursRequest;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\StoreApplicationUpcomingMeetingRequest;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\UpdateApplicationUpcomingMeetingRequest;
use App\Http\Requests\Application\ApplicationUpcomingMeeting\UpdateApplicationUpcomingMeetingStatusRequest;
use App\Http\Resources\Application\AllApplicationUpcomingMeetingResource;
use App\Http\Resources\Application\ApplicationUpcomingMeetingResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Models\Application;
use App\Models\ApplicationUpcomingMeeting;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationUpcomingMeetingRepository;
use App\Services\Application\ApplicationUpcomingMeetingService;

class ApplicationUpcomingMeetingController
{
    public function __construct(
        private readonly IApplicationUpcomingMeetingRepository $applicationUpcomingMeetingRepository,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
        private readonly ApplicationUpcomingMeetingService $applicationUpcomingMeetingService,
    )
    {

    }

    public function updateStatus(UpdateApplicationUpcomingMeetingStatusRequest $request, Application $application, ApplicationUpcomingMeeting $upcomingMeeting): SuccessResource
    {
        $user = auth()->user();
        $data = $request->validated();
        $type = null;

        $applicationUpcomingMeeting = $this->applicationUpcomingMeetingRepository->findById($application->id, $upcomingMeeting->id);

        $this->applicationUpcomingMeetingRepository->update($applicationUpcomingMeeting->id, $data);

        if ($data['status'] === ApplicationMeetingStatuses::REJECTED->value) {
            if ($user->isMediator()) {
                $type = ApplicationActivityLogTypes::MEDIATOR_REJECTED_UPCOMING_MEETING->value;
            } else {
                $type = ApplicationActivityLogTypes::REJECTED_UPCOMING_MEETING->value;
            }
        }

        if ($data['status'] === ApplicationMeetingStatuses::CONFIRMED->value) {
            if ($user->isMediator()) {
                $type = ApplicationActivityLogTypes::MEDIATOR_CONFIRMED_UPCOMING_MEETING->value;
            } else {
                $type = ApplicationActivityLogTypes::CONFIRMED_UPCOMING_MEETING->value;
            }
        }

        $this->applicationActivityLogRepository->log(
            $application->id,
            $user->id,
            $type
        );

        return SuccessResource::make([
            'message' => trans('message.application-upcoming-meeting-updated')
        ]);
    }

    public function update(UpdateApplicationUpcomingMeetingRequest $request, Application $application, ApplicationUpcomingMeeting $upcomingMeeting): SuccessResource
    {
        $data = $request->validated();

        $applicationUpcomingMeeting = $this->applicationUpcomingMeetingRepository->findById($application->id, $upcomingMeeting->id);

        $this->applicationUpcomingMeetingRepository->update($applicationUpcomingMeeting->id, $data);

        $this->applicationActivityLogRepository->logWithOldData(
            $application->id,
            auth()->id(),
            ApplicationActivityLogTypes::MEDIATOR_UPDATED_UPCOMING_MEETING->value,
            $data,
            $applicationUpcomingMeeting
        );

        return SuccessResource::make([
            'message' => trans('message.application-upcoming-meeting-updated')
        ]);
    }

    public function store(StoreApplicationUpcomingMeetingRequest $request, Application $application): SuccessResource|ErrorResource
    {
        $data = $request->validated();

        if ($application->status == ApplicationStatuses::FINISHED->value) {
            return ErrorResource::make([
                'message' => trans('message.application-is-finished')
            ]);
        }

        $upcomingMeeting = $this->applicationUpcomingMeetingRepository->create(
            $data + ['application_id' => $application->id]
        );

        //TODO Comments will be opened after the notification logic is integrated,because the are email limit
        //event(new ApplicationUpcomingMeetingCreated($application, $upcomingMeeting));

        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            ApplicationActivityLogTypes::MEDIATOR_CREATED_UPCOMING_MEETING->value,
            $data
        );

        return SuccessResource::make([
            'data' => $upcomingMeeting,
            'message' => trans('message.application-upcoming-meeting-created')
        ]);
    }

    public function index(GetApplicationUpcomingMeetingsRequest $request, Application $application): PaginationResource
    {
        $applicationUpcomingMeetings = $this->applicationUpcomingMeetingRepository->getUpcomingMeetingsBy($application->id);

        return PaginationResource::make([
            'data' => ApplicationUpcomingMeetingResource::collection($applicationUpcomingMeetings->items()),
            'pagination' => $applicationUpcomingMeetings
        ]);
    }

    public function mediatorUpcomingMeetings(GetAllApplicationUpcomingMeetingsRequest $request): SuccessResource
    {
        return SuccessResource::make([
            'data' => AllApplicationUpcomingMeetingResource::collection($this->applicationUpcomingMeetingRepository->getMediatorUpcomingMeetings(auth()->id())),
            'message' => trans('message.all-application-upcoming-meetings')
        ]);
    }

    public function destroy(DestroyApplicationUpcomingMeetingRequest $request, Application $application, ApplicationUpcomingMeeting $upcomingMeeting): SuccessResource|ErrorResource
    {
        $applicationUpcomingMeeting = $this->applicationUpcomingMeetingRepository->findById($application->id, $upcomingMeeting->id);

        $this->applicationUpcomingMeetingRepository->delete($applicationUpcomingMeeting->id);

        $this->applicationActivityLogRepository->log(
            $application->id,
            auth()->id(),
            ApplicationActivityLogTypes::MEDIATOR_REMOVED_UPCOMING_MEETING->value,
        );

        return SuccessResource::make([
            'message' => trans('message.upcoming-meeting-deleted-successful')
        ]);
    }

    public function getMediatorFreeHours(MediatorFreeHoursRequest $request): SuccessResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        return SuccessResource::make([
            'data' => $this->applicationUpcomingMeetingService->getMediatorFreeHours($data),
            'message' => trans('message.upcoming-free-hours')
        ]);

    }
}
