<?php

namespace App\Http\Resources\Mediator;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediatorCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'course' => CourseResource::make($this->resource->course),
            'mediator' => UserResource::make($this->resource->mediator),
        ];
    }
}
