<?php

declare(strict_types=1);

namespace App\Http\Resources\CaseType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'groupId' => $this->resource->group_id,
            'status' => $this->resource->status,
        ];
    }
}
