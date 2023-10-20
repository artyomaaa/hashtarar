<?php

namespace App\Http\Requests\Mediator\MediatorApplicationCaseType;

use Illuminate\Foundation\Http\FormRequest;

class GetMediatorApplicationCaseTypeRequest extends FormRequest
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
