<?php

declare(strict_types=1);

namespace App\Http\Requests\Judge\ApplicationMediator;

use App\Enums\ApplicationStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatedMediatorApplicationJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
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
            'reason' => [
                Rule::requiredIf($this->request->get('status') === ApplicationStatuses::REJECTED->value && !$this->request->get('authorized_reject')),
                'max:1000',
                'min:1'
            ],
            'citizen_id'=>[
                Rule::requiredIf($this->request->get('status') === ApplicationStatuses::CONFIRMED->value),
            ]
        ];
    }
}
