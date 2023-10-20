<?php

namespace App\Http\Requests\Judge;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'court_id' => 'required|exists:courts,id',
            'address' => 'required|min:1|max:200'
        ];
    }
}
