<?php
declare(strict_types=1);

namespace App\Http\Requests\ApplicationMediator;

use App\Enums\MediatorApplicationStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatedMediatorApplicationStatusRequest extends FormRequest
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
                    MediatorApplicationStatuses::FINISHED->value,
                    MediatorApplicationStatuses::REJECTED->value
                ])
            ],
            'reason' => [
                Rule::requiredIf($this->request->get('status') === MediatorApplicationStatuses::REJECTED->value),
                'max:1000',
                'min:1'
            ]
        ];
    }
}
