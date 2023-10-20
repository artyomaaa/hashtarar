<?php

namespace App\Services\Auth;

use App\Mail\ResetPassword;
use App\Repositories\Contracts\IPasswordResetRepository;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForgotPasswordService
{
    public function __construct(
        private IUserRepository          $userRepository,
        private IPasswordResetRepository $passwordResetRepository,
    )
    {

    }

    public function resetPassword($data)
    {
        $token = $this->passwordResetRepository->findBy($data['email'], $data['token']);

        if (!$token) {
            throw new NotFoundHttpException('Token not found');
        }

        if($token->isExpired()){
            throw new BadRequestHttpException('Token expired');
        }

        $user = $this->userRepository->findByEmail($token->email);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $this->userRepository->update($user->id, [
            'password' => Hash::make($data['password'])
        ]);

        $this->passwordResetRepository->deleteByEmail($data['email']);
    }

    public function forgotPassword($data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $token = $this->passwordResetRepository->findByEmail($data['email']);

        if (!$token || $token->isExpired()) {
            $token = $this->passwordResetRepository->create([
                'email' => $data['email'],
                'token' => Str::random(64),
            ]);
        }

        Mail::to($data['email'])->send(new ResetPassword([
            'token' => $token->token,
            'email' => $token->email,
        ]));
    }
}
