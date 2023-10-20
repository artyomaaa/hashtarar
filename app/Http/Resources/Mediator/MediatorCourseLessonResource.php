<?php

namespace App\Http\Resources\Mediator;

use App\Http\Resources\Course\CourseLessonResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediatorCourseLessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'courseLesson' => CourseLessonResource::make($this->resource->courseLesson),
            'mediator' => UserResource::make($this->resource->mediator),
        ];
    }
}
