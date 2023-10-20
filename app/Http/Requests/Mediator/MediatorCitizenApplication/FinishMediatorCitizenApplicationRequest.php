<?php

namespace App\Http\Requests\Mediator\MediatorCitizenApplication;

use App\Enums\ApplicationResultStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FinishMediatorCitizenApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('update', $this->route('application')) && $user->isMediator();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(ApplicationResultStatuses::class)],
            'message' => 'required|min:1|max:1000'
        ];
    }
}
