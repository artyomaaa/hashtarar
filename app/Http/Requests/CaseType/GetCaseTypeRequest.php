<?php

namespace App\Http\Requests\CaseType;

use Illuminate\Foundation\Http\FormRequest;

class GetCaseTypeRequest extends FormRequest
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
