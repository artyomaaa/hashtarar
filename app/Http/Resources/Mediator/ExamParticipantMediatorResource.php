<?php

namespace App\Http\Resources\Mediator;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamParticipantMediatorResource extends JsonResource
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
            'role_id' => $this->resource->role_id,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'email' => $this->resource->email,
        ];
    }
}
