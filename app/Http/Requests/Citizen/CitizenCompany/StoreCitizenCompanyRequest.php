<?php

declare(strict_types=1);

namespace App\Http\Requests\Citizen\CitizenCompany;

use Illuminate\Foundation\Http\FormRequest;

class StoreCitizenCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|string|min:1',
            'address' => 'required|string|min:1|max:200',
            'registration_number' => 'required|string|min:1|max:200',
            'name_of_representative' => 'required|string|min:1|max:200',
        ];
    }
}
