<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Mail\ChangeEmail;
use App\Models\User;
use App\Repositories\Contracts\IPasswordResetRepository;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    public function __construct(
        private IUserRepository          $userRepository,
        private IPasswordResetRepository $passwordResetRepository,
    )
    {

    }

    public function changePassword($data)
    {
        $this->userRepository->update(auth()->user()->id, [
            'password' => Hash::make($data['password'])
        ]);
    }

    public function changeEmail($data)
    {
        $token = $this->passwordResetRepository->findByEmail($data['email']);

        if (!$token || $token->isExpired()) {
            $token = $this->passwordResetRepository->create([
                'email' => $data['email'],
                'token' => Str::random(64),
            ]);
        }

        Mail::to($data['email'])->send(new ChangeEmail([
            'token' => $token->token,
            'email' => $token->email,
        ]));
    }

    public function changeEmailConfirm($data)
    {
        $token = $this->passwordResetRepository->findBy($data['email'], $data['token']);

        if (!$token) {
            throw new NotFoundHttpException('Token not found');
        }

        if ($token->isExpired()) {
            throw new BadRequestHttpException('Token expired');
        }

        $this->userRepository->update(auth()->user()->id, [
            'email' => $data['email']
        ]);

        $this->passwordResetRepository->deleteByEmail($data['email']);
    }

    public function getUserBySsn(string $ssn): User|null
    {
        return $this->userRepository->firstOrFailBySsn($ssn);
    }
}
