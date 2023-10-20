<?php

namespace App\Http\Requests\Role;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

class GetRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
