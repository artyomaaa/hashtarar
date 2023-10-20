<?php

namespace App\Http\Requests\Judge;

use Illuminate\Foundation\Http\FormRequest;

class GetJudgesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
