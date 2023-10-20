<?php

namespace App\Http\Controllers\Role;

use App\Http\Requests\Role\GetRolesRequest;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Contracts\IRoleRepository;

class RoleController
{
    public function __construct(
        private IRoleRepository $roleRepository,
    )
    {

    }

    public function index(GetRolesRequest $request): SuccessResource
    {
        return SuccessResource::make([
            'data' => RoleResource::collection($this->roleRepository->getRoles()),
        ]);
    }
}
