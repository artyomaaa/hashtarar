<?php

namespace App\Http\Requests\Mediator\MediatorApplicationCaseType;

use Illuminate\Foundation\Http\FormRequest;

class DestroyMediatorApplicationCaseTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
