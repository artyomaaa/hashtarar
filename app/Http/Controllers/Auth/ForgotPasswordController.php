<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Services\Auth\ForgotPasswordService;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private ForgotPasswordService $forgotPasswordService,
    ) {

    }

    public function forgotPassword(ForgotPasswordRequest $request): SuccessResource|ErrorResource
    {
        $this->forgotPasswordService->forgotPassword($request->validated());

        return SuccessResource::make([
            'message' => 'Link for reset password successfully sent'
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): SuccessResource|ErrorResource
    {
        $this->forgotPasswordService->resetPassword($request->validated());

        return SuccessResource::make([
            'message' => 'Password successfully set'
        ]);
    }
}
