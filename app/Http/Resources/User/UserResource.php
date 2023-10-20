<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Enums\UserRoles;
use App\Http\Resources\Citizen\CitizenCompanyResource;
use App\Http\Resources\Judge\JudgeResource;
use App\Http\Resources\Mediator\MediatorResource;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'middlename' => $this->resource->middlename,
            'ssn' => $this->resource->ssn,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'birthdate' => $this->resource->birthdate,
            'createdAt' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'role' => RoleResource::make($this->resource->role),
            'citizenCompany' => CitizenCompanyResource::make($this->resource->citizenCompany),
            'course' => $this->resource->mediatorCourse,
            'mediatorDetails' => $this->when(
                $this->resource->role?->name === UserRoles::MEDIATOR->value,
                MediatorResource::make($this->whenLoaded('mediatorDetails'))
            ),
            'judgeDetails' => $this->when(
                $this->resource->role?->name === UserRoles::JUDGE->value,
                JudgeResource::make($this->whenLoaded('judgeDetails'))
            )
        ];
    }
}
