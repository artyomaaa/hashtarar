<?php

namespace App\Http\Requests\Judge;

use Illuminate\Foundation\Http\FormRequest;

class GetJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('judgeDetails')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
