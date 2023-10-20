<?php

namespace App\Http\Controllers\Application;

use App\Http\Requests\Application\ApplicationActivityLog\GetApplicationActivityLogsRequest;
use App\Http\Resources\Application\ApplicationActivityLogResource;
use App\Http\Resources\PaginationResource;
use App\Models\Application;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;

class ApplicationActivityLogController
{
    public function __construct(
        private IApplicationActivityLogRepository $applicationActivityLogRepository,
    )
    {

    }

    public function index(GetApplicationActivityLogsRequest $request, Application $application): PaginationResource
    {
        $activityLogs = $this->applicationActivityLogRepository->getActivityLogsBy($application->id);

        return PaginationResource::make([
            'data' => ApplicationActivityLogResource::collection($activityLogs->items()),
            'pagination' => $activityLogs
        ]);
    }
}
