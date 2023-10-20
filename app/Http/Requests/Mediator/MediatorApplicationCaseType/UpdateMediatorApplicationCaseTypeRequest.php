<?php

namespace App\Http\Requests\Mediator\MediatorApplicationCaseType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediatorApplicationCaseTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:1|max:100',
        ];
    }
}
