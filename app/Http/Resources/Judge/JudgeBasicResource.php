<?php

namespace App\Http\Resources\Judge;

use App\Http\Resources\Court\CourtResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JudgeBasicResource extends JsonResource
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
            'user_id' => $this->resource->user_id,
            'address' => $this->resource->address,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'court' => CourtResource::make($this->resource->court),
        ];
    }
}
