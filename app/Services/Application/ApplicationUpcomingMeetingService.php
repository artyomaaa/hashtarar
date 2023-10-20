<?php

namespace App\Services\Application;

use App\Repositories\Contracts\Application\IApplicationUpcomingMeetingRepository;


class ApplicationUpcomingMeetingService
{
    public function __construct(
        private readonly IApplicationUpcomingMeetingRepository $applicationUpcomingMeetingRepository,
    )
    {

    }

    public function getMediatorFreeHours(array $data): array
    {
        $start = '00:00';
        $end = '23:59';
        $start = strtotime($start);
        $end = strtotime($end);
        $hours = [];

        for ($i = 0; $i <= $end - $start; $i += 60) {
            $hours[] = date('H:i', $start + $i);
        }

        $mediatorUpcomingMeetingHour = $this->applicationUpcomingMeetingRepository->getMediatorUpcomingMeetingHours($data['user_id'], $data['date']);

        foreach ($mediatorUpcomingMeetingHour as $value) {
            foreach ($hours as $key => $hour) {
                if ($hour >= $value['start'] && $hour <= $value['end']) {
                    unset($hours[$key]);
                }
            }
        }
        return $hours;

    }

}
