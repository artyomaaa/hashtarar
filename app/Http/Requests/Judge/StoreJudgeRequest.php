<?php

namespace App\Http\Requests\Judge;

use Illuminate\Foundation\Http\FormRequest;

class StoreJudgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'address' => 'required|min:1|max:200'
        ];
    }
}
