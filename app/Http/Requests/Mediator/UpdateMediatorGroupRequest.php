<?php

namespace App\Http\Requests\Mediator;

use App\Enums\CaseTypeGroups;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediatorGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $hasGroupId = $this->request->has('group_id');

        return [
            'group_id' => [
                Rule::requiredIf((!is_null($this->request->get('group_id')) && $hasGroupId) || !$hasGroupId),
                Rule::in([
                    null,
                    CaseTypeGroups::LIST_1->value,
                    CaseTypeGroups::LIST_2->value,
                    CaseTypeGroups::LIST_3->value,
                ])
            ]
        ];
    }
}
