<?php

declare(strict_types=1);

namespace App\Http\Resources\Course;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'durationHours' => $this->resource->duration_hours,
            'startDate' => $this->resource->start_date,
            'endDate' => $this->resource->end_date,
            'minHoursForExam' => $this->resource->min_hours_for_exam,
            'isTraining' => (bool)$this->resource->is_training,
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'mediators' => $this->resource->mediators,
            'exam' => $this->resource->exam,
            'lessons' => $this->resource->lessons,
        ];
    }
}
