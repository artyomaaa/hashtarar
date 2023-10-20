<?php

namespace App\Http\Resources\Application;

use App\Enums\ApplicationStatuses;
use App\Http\Resources\CaseType\CaseTypeResource;
use App\Http\Resources\User\UserResource;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    private IApplicationActivityLogRepository $applicationActivityLogRepository;

    public function __construct($resource)
    {
        $this->applicationActivityLogRepository = app(IApplicationActivityLogRepository::class);

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $applicationPath = explode('/', $this->resource->application);

        return [
            'id' => $this->resource->id,
            'number' => $this->resource->number,
            'status' => $this->resource->status,
            'application' => end($applicationPath),
            'rejectionReason' => $this->when(
                $this->resource->status === ApplicationStatuses::REJECTED->value,
                $this->applicationActivityLogRepository->getLatestRejectionReason($this->resource->id)?->data['reason'] ?? null
            ),
            'result' => $this->when(
                $this->resource->status === ApplicationStatuses::FINISHED->value,
                $this->resource->result
            ),
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'caseType' => CaseTypeResource::make($this->resource->caseType),
            'mediator' => UserResource::make($this->whenLoaded('mediator')),
            'citizen' => UserResource::make($this->whenLoaded('citizen')),
            'judge' => UserResource::make($this->whenLoaded('judge')),
            'attachments' => ApplicationAttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
