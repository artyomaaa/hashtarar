<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Enums\ApplicationActivityLogTypes;
use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeUserInfoRequest;
use App\Http\Requests\User\ChangeEmailConfirmRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ChangeUserPhoneNumberRequest;
use App\Http\Requests\User\CheckUserPasswordRequest;
use App\Http\Requests\User\GetUserBySsnRequest;
use App\Http\Requests\User\GetUsersFullInfoRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdatedUserOtherMeansRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserPersonalResource;
use App\Http\Resources\User\UserResource;
use App\Repositories\Contracts\Application\IApplicationActivityLogRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService,
        private readonly IUserRepository $userRepository,
        private readonly IApplicationActivityLogRepository $applicationActivityLogRepository,
    )
    {

    }

    public function store(StoreUserRequest $request): SuccessResource
    {
        $data = $request->validated();
        $user = $this->authService->register($data);

        if (isset($data['application_id']) && $data['role'] === UserRoles::CITIZEN->value) {
            $this->applicationActivityLogRepository->log(
                $data['application_id'],
                auth()->id(),
                ApplicationActivityLogTypes::CITIZEN_ADDED->value,
                $data
            );
        }

        return SuccessResource::make([
            'data' => UserResource::make($user),
            'message' => trans('message.user-created')
        ]);
    }

    public function getBySsn(GetUserBySsnRequest $request, string $ssn): SuccessResource
    {
        $user = $this->userService->getUserBySsn($ssn);

        return SuccessResource::make([
            'data' => UserResource::make($user),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): SuccessResource
    {
        $this->userService->changePassword($request->validated());

        return SuccessResource::make([
            'message' => 'Password changed',
        ]);
    }

    public function changeEmail(ChangeEmailRequest $request): SuccessResource
    {
        $this->userService->changeEmail($request->validated());

        return SuccessResource::make([
            'message' => 'Link for change email successfully sent'
        ]);
    }

    public function changeEmailConfirm(ChangeEmailConfirmRequest $request): SuccessResource
    {
        $this->userService->changeEmailConfirm($request->validated());

        return SuccessResource::make([
            'message' => 'Email changed',
        ]);
    }

    public function checkPassword(CheckUserPasswordRequest $request): SuccessResource|ErrorResource
    {
        $data = $request->validated();
        $isExistUser = $this->userRepository->findById((int)$data['id']);

        if (!$isExistUser) {
            return ErrorResource::make([
                'message' => trans('message.not-found')
            ]);
        }

        if (!Hash::check($data['password'], $isExistUser['password'])) {
            return ErrorResource::make([
                'message' => trans('message.passwords-do-not-match')
            ]);
        }

        return SuccessResource::make([
            'message' => trans('message.passwords-match'),
        ]);

    }

    public function changePhoneNumber(ChangeUserPhoneNumberRequest $request): SuccessResource
    {
        $data = $request->validated();
        $this->userRepository->update($data['id'], ['phone' => $data['phone_number']]);
        return SuccessResource::make([
            'message' => trans('message.updated-successfully'),
        ]);
    }

    public function updatedOtherMeans(UpdatedUserOtherMeansRequest $request): SuccessResource
    {
        $data = $request->validated();
        return SuccessResource::make([
            'data' => $this->userRepository->updateAndGetUpdatedData('id', $data['id'], ['other_means' => $data['other_means']]),
            'message' => trans('message.updated-successfully'),
        ]);

    }

    public function getPersonalInfo(GetUsersFullInfoRequest $request, int $id): SuccessResource
    {
        return SuccessResource::make([
            'data' => UserPersonalResource::make($this->userRepository->find($id)),
        ]);
    }


    public function changeUserInfo(ChangeUserInfoRequest $request): SuccessResource
    {
        //TODO later it will also be possible to change: notification address, legal entity data
        $data = $request->validated();
        if ($request->has('password')) {
            $this->userRepository->update((int)$data['id'], [
                'password' => Hash::make($data['password'])
            ]);
            unset($data['password']);
        }

        return SuccessResource::make([
            'data' => $this->userRepository->updateAndGetUpdatedData('id', (int)$data['id'], $data),
            'message' => trans('message.updated-successfully'),
        ]);
    }

}
