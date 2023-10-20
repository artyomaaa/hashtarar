<?php

namespace App\Http\Requests\Course\CourseLesson;

use Illuminate\Foundation\Http\FormRequest;

class GetCourseLessonsRequest extends FormRequest
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
