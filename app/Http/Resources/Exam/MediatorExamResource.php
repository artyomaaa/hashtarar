<?php

namespace App\Http\Resources\Exam;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediatorExamResource extends JsonResource
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
            'exam_result' => $this->resource->exam_result,
            'qualification' => $this->resource->qualifications,
        ];
    }
}
