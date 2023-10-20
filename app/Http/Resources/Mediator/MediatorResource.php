<?php

namespace App\Http\Resources\Mediator;

use App\Http\Resources\Exam\MediatorExamResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediatorResource extends JsonResource
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
            'group_id' => $this->resource->group_id,
            'status' => $this->resource->status,
            'mediator_specialization' => $this->resource->mediator_specialization,
            'mediator_company' => MediatorCompanyResource::make($this->resource->company),
            'qualifications' => MediatorExamResource::make($this->resource->mediatorExams),
            'cv' => $this->resource->cv ? Storage::disk('public')->url($this->resource->cv) : null,
            'avatar' => $this->resource->avatar ? Storage::disk('public')->url($this->resource->avatar) : null,
            'hadLicenseBefore' => (bool)$this->resource->had_license_before,
            'inProgressApplicationsCount' => $this->whenHas('in_progress_applications_count'),
            'finishedApplicationsCount' => $this->whenHas('finished_applications_count'),
            'rejectedApplicationsCount' => $this->whenHas('rejected_applications_count'),
            'resolvedApplicationsCount' => $this->whenHas('resolved_applications_count'),
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'qualification' => $this->resource->user?->mediatorExams?->exam_result ? $this->resource->user?->mediatorExams?->course?->name : null,
            'qualificationDate' => $this->resource->user?->mediatorExams?->exam_result ? date('Y-m-d', strtotime($this->resource->user->mediatorExams->exam_date)) : null,
            'user' => UserResource::make($this->whenLoaded('user')),
            'attachments' => $this->when(
                $user?->isEmployeeOrAdmin() || $user?->id === $this->resource->user_id,
                MediatorAttachmentResource::collection($this->whenLoaded('attachments'))
            ),
        ];
    }
}
