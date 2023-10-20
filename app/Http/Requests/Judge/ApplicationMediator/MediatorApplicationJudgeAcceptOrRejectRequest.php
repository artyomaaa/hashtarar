<?php

declare(strict_types=1);

namespace App\Http\Requests\Judge\ApplicationMediator;

use App\Enums\ApplicationStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediatorApplicationJudgeAcceptOrRejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isJudge();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(
                [
                    ApplicationStatuses::CONFIRMED->value,
                    ApplicationStatuses::REJECTED->value
                ])
            ],
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
