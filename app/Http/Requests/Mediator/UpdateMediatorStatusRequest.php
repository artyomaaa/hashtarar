<?php

namespace App\Http\Requests\Mediator;

use App\Enums\MediatorStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediatorStatusRequest extends FormRequest
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
                    MediatorStatuses::ACTIVE->value,
                    MediatorStatuses::TERMINATED->value,
                    MediatorStatuses::SUSPENDED->value,
                ]
            )],
        ];
    }
}
