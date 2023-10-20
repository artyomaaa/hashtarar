<?php

namespace App\Http\Requests\Mediator;

use Illuminate\Foundation\Http\FormRequest;

class GetMediatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('mediatorDetails')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
