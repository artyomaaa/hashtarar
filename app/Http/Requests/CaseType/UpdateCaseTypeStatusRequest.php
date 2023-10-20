<?php

declare(strict_types=1);


namespace App\Http\Requests\CaseType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCaseTypeStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|boolean',
        ];
    }
}
