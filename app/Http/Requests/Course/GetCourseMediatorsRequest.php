<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class GetCourseMediatorsRequest extends FormRequest
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
