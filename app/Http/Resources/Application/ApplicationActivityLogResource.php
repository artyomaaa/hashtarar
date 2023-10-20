<?php

namespace App\Http\Resources\Application;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'application' => [
                "id" => $this->resource->application->id,
                "number" => $this->resource->application->number,
            ],
            'type' => $this->resource->type,
            'data' => $this->resource->data,
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'user' => UserResource::make($this->resource->user),
        ];
    }
}
