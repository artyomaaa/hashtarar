<?php

namespace App\Http\Requests\Mediator\MediatorCitizenApplication;

use App\Enums\ApplicationStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediatorCitizenApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('update', $this->route('application')) && $user->isMediator();
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
            'authorized_reject' => 'required|boolean',
        ];
    }
}
