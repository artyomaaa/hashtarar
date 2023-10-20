<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Models\ApplicationMeetingRecording;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationMeetingRecordingRepository as ApplicationMeetingRecordingRepositoryContract;

final class ApplicationMeetingRecordingRepository
    extends BaseRepository
    implements ApplicationMeetingRecordingRepositoryContract
{
    public function __construct(ApplicationMeetingRecording $model)
    {
        parent::__construct($model);
    }

    public function findById(int $applicationId, int $meetingId, int $recordingId): ApplicationMeetingRecording|null
    {
        return $this->model
            ->where('id', $recordingId)
            ->where('meeting_id', $meetingId)
            ->whereHas('meeting.application', function ($query) use ($applicationId) {
                $query->where('id', $applicationId);
            })
            ->firstOrFail();
    }

    public function getByIds(int $meetingId, array $attachmentIds)
    {
        return $this->model
            ->whereIn('id', $attachmentIds)
            ->where('meeting_id', $meetingId)
            ->get();
    }
}
