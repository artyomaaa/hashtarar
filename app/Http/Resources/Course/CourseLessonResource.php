<?php

declare(strict_types=1);

namespace App\Http\Resources\Course;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseLessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'address' => $this->resource->address,
            'date' => Carbon::parse($this->resource->date)->format('Y-m-d'),
            'time' => Carbon::parse($this->resource->date)->format('H:i:s'),
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'course' => CourseResource::make($this->whenLoaded('course')),
        ];
    }
}
