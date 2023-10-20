<?php

namespace App\Http\Requests\CaseType;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCaseTypeRequest extends FormRequest
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
