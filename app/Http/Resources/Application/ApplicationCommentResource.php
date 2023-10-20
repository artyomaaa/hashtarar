<?php

namespace App\Http\Resources\Application;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'message' => $this->resource->message,
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'commentator' => UserResource::make($this->resource->commentator),
        ];
    }
}
