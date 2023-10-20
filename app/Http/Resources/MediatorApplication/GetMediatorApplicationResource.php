<?php

declare(strict_types=1);

namespace App\Http\Resources\MediatorApplication;

use App\Http\Resources\CaseType\CaseTypeResource;
use App\Http\Resources\User\UserPersonalResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetMediatorApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'number' => $this->resource->number,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at->format('Y-m-d'),
            'application_case_type' => CaseTypeResource::make($this->resource->caseTypes),
            'application_cause' => $this->resource->application_cause,
            'mediator' => UserPersonalResource::make($this->resource->mediator),
        ];
    }
}
