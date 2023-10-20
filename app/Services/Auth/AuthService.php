<?php

namespace App\Services\Auth;

use App\Enums\UserRoles;
use App\Repositories\Contracts\IRoleRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Judge\IJudgeRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository;

class AuthService
{
    public function __construct(
        private IUserRepository       $userRepository,
        private IRoleRepository       $roleRepository,
        private IMediatorRepository   $mediatorRepository,
        private IJudgeRepository      $judgeRepository,
        private ForgotPasswordService $forgotPasswordService,
    )
    {

    }

    public function register($data)
    {
        $roleId = $this->roleRepository->findByName($data['role'])?->id;
        $data['role_id'] = $roleId;

        //TODO Validate user with API if data is incorrect return error

        $user = $this->userRepository->create($data);

        $this->forgotPasswordService->forgotPassword([
            'email' => $data['email']
        ]);

        if ($data['role'] === UserRoles::MEDIATOR->value) {
            $this->mediatorRepository->create([
                'user_id' => $user->id
            ]);
        }

        if ($data['role'] === UserRoles::JUDGE->value) {
            $this->judgeRepository->create([
                'user_id' => $user->id
            ]);
        }

        return $user;
    }
}
