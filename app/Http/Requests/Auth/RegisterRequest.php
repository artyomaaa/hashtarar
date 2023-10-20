<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ssn' => 'required|unique:users|min:6|max:20',
            'firstname' => 'required|min:1|max:50',
            'lastname' => 'required|min:1|max:50',
            'middlename' => 'required|min:1|max:50',
            'birthdate' => 'required|date_format:Y-m-d|before:today',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'role' => [
                'required',
                Rule::in(
                    [
                        UserRoles::MEDIATOR->value,
                        UserRoles::CITIZEN->value
                    ]
                )
            ]
        ];
    }
}
