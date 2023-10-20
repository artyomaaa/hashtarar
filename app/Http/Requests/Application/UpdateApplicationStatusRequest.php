<?php

namespace App\Http\Requests\Application;

use App\Enums\ApplicationStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationStatusRequest extends FormRequest
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
                Rule::requiredIf($this->request->get('status') === ApplicationStatuses::REJECTED->value),
                'max:1000',
                'min:1'
            ]
        ];
    }
}
