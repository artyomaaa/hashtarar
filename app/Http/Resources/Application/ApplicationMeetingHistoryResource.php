<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ApplicationMeetingHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'applicationId' => $this->resource->application_id,
            'type' => $this->resource->type,
            'address' => $this->resource->address,
            'information' => $this->resource->information,
            'planning' => $this->resource->planning,
            'date' => Carbon::parse($this->resource->date)->format('Y-m-d H:i:s'),
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'recordings' => ApplicationMeetingRecordingResource::collection($this->whenLoaded('recordings')),
        ];
    }
}
