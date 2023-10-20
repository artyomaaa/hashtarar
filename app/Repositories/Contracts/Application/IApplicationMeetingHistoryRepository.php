<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

interface IApplicationMeetingHistoryRepository
{
    public function findById(int $meetingHistoryId, int $applicationId);
    public function getMeetingHistoriesBy(int $applicationId);
}
