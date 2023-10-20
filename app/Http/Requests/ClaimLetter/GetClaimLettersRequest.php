<?php

namespace App\Http\Requests\ClaimLetter;

use Illuminate\Foundation\Http\FormRequest;

class GetClaimLettersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
