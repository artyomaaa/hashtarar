<?php

namespace App\Http\Resources\Mediator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediatorBasicResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'group_id' => $this->resource->group_id,
            'user_id' => $this->resource->user_id,
            'mediator_company_id' => $this->resource->mediator_company_id,
            'status' => $this->resource->status,
            'mediator_specialization' => $this->resource->mediator_specialization,
            'mediator_company' => MediatorCompanyResource::make($this->resource->company),
            'cv' => $this->resource->cv ? Storage::disk('public')->url($this->resource->cv) : null,
            'avatar' => $this->resource->avatar ? Storage::disk('public')->url($this->resource->avatar) : null,
            'had_license_before' => (bool)$this->resource->had_license_before,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),
            'attachments' => MediatorAttachmentResource::collection($this->resource->attachments),
        ];
    }
}
