<?php

namespace App\Http\Requests\Mediator\MediatorCitizenApplication;

use Illuminate\Foundation\Http\FormRequest;

class GetMediatorCitizenApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isMediator();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
