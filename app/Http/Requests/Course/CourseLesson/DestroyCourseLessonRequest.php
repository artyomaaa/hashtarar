<?php

namespace App\Http\Requests\Course\CourseLesson;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCourseLessonRequest extends FormRequest
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
