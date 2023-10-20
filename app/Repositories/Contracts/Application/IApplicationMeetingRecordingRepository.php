<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

interface IApplicationMeetingRecordingRepository
{
    public function findById(int $applicationId, int $meetingId, int $recordingId);

    public function getByIds(int $meetingId, array $attachmentIds);
}
