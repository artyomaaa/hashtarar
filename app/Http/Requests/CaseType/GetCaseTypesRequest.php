<?php

namespace App\Http\Requests\CaseType;

use Illuminate\Foundation\Http\FormRequest;

class GetCaseTypesRequest extends FormRequest
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
