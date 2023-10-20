<?php

namespace App\Http\Requests\CaseType;

use App\Enums\CaseTypeGroups;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCaseTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:1|max:100',
            'group_id' => ['required', new Enum(CaseTypeGroups::class)]
        ];
    }
}
