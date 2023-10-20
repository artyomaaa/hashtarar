<?php

namespace App\Http\Resources\Judge;

use App\Http\Resources\Court\CourtResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JudgeResource extends JsonResource
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
        $user = $request->user();

        return [
            'id' => $this->resource->id,
            'address' => $this->resource->address,
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'user' => UserResource::make($this->whenLoaded('user')),
            'court' => CourtResource::make($this->whenLoaded('court')),
        ];
    }
}
