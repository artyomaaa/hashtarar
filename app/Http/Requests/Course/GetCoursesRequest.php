<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class GetCoursesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin() || $user->isMediator();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
