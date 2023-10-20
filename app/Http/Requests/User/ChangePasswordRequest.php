<?php

namespace App\Http\Requests\User;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', new MatchOldPassword()],
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
