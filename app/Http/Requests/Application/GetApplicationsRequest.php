<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class GetApplicationsRequest extends FormRequest
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
