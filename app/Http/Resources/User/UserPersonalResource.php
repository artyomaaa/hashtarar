<?php

declare(strict_types=1);

namespace App\Http\Resources\User;


use App\Http\Resources\Judge\JudgeBasicResource;
use App\Http\Resources\Mediator\MediatorBasicResource;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPersonalResource extends JsonResource
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
            'mediatorDetails' => MediatorBasicResource::make($this->resource->mediatorDetails),
            'judgeDetails' => JudgeBasicResource::make($this->resource->judgeDetails),
        ];
    }
}
