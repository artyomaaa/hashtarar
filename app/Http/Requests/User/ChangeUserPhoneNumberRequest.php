<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPhoneNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'id' => 'required|integer|exists:users,id',
            'phone_number' => 'required|integer|digits:9',
        ];
    }
}
