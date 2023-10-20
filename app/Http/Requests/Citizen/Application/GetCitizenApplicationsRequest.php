<?php

namespace App\Http\Requests\Citizen\Application;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

class GetCitizenApplicationsRequest extends FormRequest
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
