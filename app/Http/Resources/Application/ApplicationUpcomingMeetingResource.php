<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ApplicationUpcomingMeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'day' => Carbon::parse($this->resource->date)->format('Y-m-d'),
            'time' => Carbon::parse($this->resource->date)->format('H:i:s'),
            'type' => $this->resource->type,
            'address' => $this->resource->address,
            'url' => $this->resource->url,
            'code' => $this->resource->code,
            'status' => $this->resource->status,
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'start' => $this->resource->start,
            'end' => $this->resource->end,
        ];
    }
}
