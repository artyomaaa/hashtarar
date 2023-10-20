<?php

namespace App\Http\Requests\Mediator;

use App\Enums\CaseTypeGroups;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMediatorCVRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isMediator() || $user->isAdmin();
    }

    public function rules(): array
    {
        return [
            'cv' => 'required|mimes:pdf,docx|max:2048',
            'id' => 'required|integer|exists:users,id',
        ];
    }
}
