<?php

declare(strict_types=1);

namespace App\Http\Requests\Mediator\MediatorCompany;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediatorCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin() || auth()->user()->isMediator();
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|string|min:1'

        ];
    }
}
