<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyEmployeeRequest;
use App\Http\Requests\Admin\GetAllStaffRequest;
use App\Http\Requests\Admin\GetEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Repositories\UserRepository;

final class AdminController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository)
    {

    }

    public function index(GetAllStaffRequest $request): PaginationResource
    {
        $allStaff = $this->userRepository->getAllStaffOrEmployee();
        return PaginationResource::make([
            'data' => UserResource::collection($allStaff->items()),
            'pagination' => $allStaff
        ]);
    }

    public function show(GetEmployeeRequest $request, int $id): ErrorResource|PaginationResource
    {
        $isExistEmployee = $this->userRepository->find($id);

        if ($isExistEmployee) {
            $employee = $this->userRepository->getAllStaffOrEmployee($id);
            return PaginationResource::make([
                'data' => UserResource::collection($employee->items()),
                'pagination' => $employee
            ]);

        }

        return ErrorResource::make([
            'message' => trans('message.not-found')
        ]);
    }

    public function update(UpdateEmployeeRequest $request, int $id): SuccessResource|ErrorResource
    {
        $isExistEmployee = $this->userRepository->find($id);
        if ($isExistEmployee) {
            $this->userRepository->update($id, $request->validated());
            return SuccessResource::make([
                'message' => trans('message.employee-updated-successful')
            ]);
        }

        return ErrorResource::make([
            'message' => trans('message.not-found')
        ]);
    }

    public function destroy(DestroyEmployeeRequest $request, int $id): SuccessResource|ErrorResource
    {
        $user = $this->userRepository->findOrFail($id);

        if ($user->isCitizen() || $user->isMediator()) {
            return ErrorResource::make([
                'message' => trans('message.access-denied')
            ]);
        }

        $this->userRepository->update($id, [
            'role_id' => UserRoles::getRoleId(UserRoles::CITIZEN)
        ]);

        return SuccessResource::make([
            'message' => trans('message.employee-deleted-successful')
        ]);
    }
}
