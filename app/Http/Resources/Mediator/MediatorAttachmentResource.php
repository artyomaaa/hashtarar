<?php

namespace App\Http\Resources\Mediator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediatorAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'createdAt' => $this->resource->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
