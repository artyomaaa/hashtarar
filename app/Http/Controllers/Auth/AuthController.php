<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\User\UserResource;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService     $authService,
        private IUserRepository $userRepository,
    )
    {

    }

    public function login(LoginRequest $request)

    {
        dd(111111);
        $token = Auth::attempt($request->only(['email', 'password']));

        if (!$token) {
            return ValidationException::withMessages(['credentials' => 'Wrong credentials']);
        }

        return SuccessResource::make([
            'data' => [
                'user' => UserResource::make($this->userRepository->findById(auth()->id())),
                'auth' => [
                    'token' => $token
                ]
            ]
        ]);
    }

    public function user(): SuccessResource
    {
        return SuccessResource::make([
            'data' => [
                'user' => UserResource::make($this->userRepository->findById(auth()->id())),
            ]
        ]);
    }

    public function register(RegisterRequest $request): SuccessResource
    {
        $this->authService->register($request->validated());

        return SuccessResource::make([
            'message' => "User registered"
        ]);
    }

    public function logout(): SuccessResource
    {
        auth()->logout();

        return SuccessResource::make([
            'message' => 'Successfully logout'
        ]);
    }

    public function refresh()
    {
        return SuccessResource::make([
            'data' => [
                'user' => UserResource::make($this->userRepository->findById(auth()->id())),
                'auth' => [
                    'token' => Auth::refresh()
                ]
            ]
        ]);
    }
}
