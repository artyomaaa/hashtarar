<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Application;

interface IApplicationUpcomingMeetingRepository
{
    public function findById(int $applicationId, int $upcomingMeetingId);

    public function getUpcomingMeetingsBy(int $applicationId);

    public function getUpcomingMeetingsByDateRange($startDate, $endDate);

    public function getMediatorUpcomingMeetings(int $id);

    public function getMediatorUpcomingMeetingHours(int $userId, $date);
}
