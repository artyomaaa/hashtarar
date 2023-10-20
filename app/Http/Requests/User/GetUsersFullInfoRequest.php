<?php

declare(strict_types=1);


namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class GetUsersFullInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
