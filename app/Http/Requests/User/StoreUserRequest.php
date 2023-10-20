<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        $user = auth()->user();

        $roleValidation = new Enum(UserRoles::class);

        if ($user->isEmployee()) {
            $roleValidation = Rule::in([UserRoles::CITIZEN->value, UserRoles::MEDIATOR->value, UserRoles::JUDGE->value]);
        }

        return [
            'ssn' => 'required|min:6|max:20|unique:users',
            'firstname' => 'required|min:1|max:50',
            'lastname' => 'required|min:1|max:50',
            'middlename' => 'required|min:1|max:50',
            'birthdate' => 'required|date_format:Y-m-d|before:today',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'role' => ['required', $roleValidation],
            'application_id' => 'exists:applications,id',
        ];
    }
}
