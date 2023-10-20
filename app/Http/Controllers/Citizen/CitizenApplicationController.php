<?php

namespace App\Http\Controllers\Citizen;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\ApplicationStatuses;
use App\Http\Requests\Citizen\Application\GetCitizenApplicationsRequest;
use App\Http\Requests\Citizen\Application\StoreCitizenApplicationRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\Application\IApplicationRepository;
use App\Services\Application\ApplicationAttachmentService;
use App\Services\Application\ApplicationService;

class CitizenApplicationController
{
    public function __construct(
        private readonly IApplicationRepository $applicationRepository,
        private readonly ApplicationAttachmentService $applicationAttachmentService,
        private readonly ApplicationService $applicationService,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
    )
    {

    }

    public function store(StoreCitizenApplicationRequest $request): SuccessResource
    {
        $data = $request->validated();
        $data['citizen_id'] = auth()->id();
        $data['status'] = ApplicationStatuses::NEW->value;
        $application = $this->applicationRepository->create($data);

        if ($application) {
            $this->applicationService->store($application, $data['application']);
            $this->applicationAttachmentService->store($application, $data['attachments']);
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

    public function index(GetCitizenApplicationsRequest $request): PaginationResource
    {
        $applications = $this->applicationRepository->getCitizenApplicationsBy(auth()->id());

        return PaginationResource::make([
            'data' => ApplicationResource::collection($applications->items()),
            'pagination' => $applications
        ]);
    }
}
