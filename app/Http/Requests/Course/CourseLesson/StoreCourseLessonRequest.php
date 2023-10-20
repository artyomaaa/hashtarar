<?php

namespace App\Http\Requests\Course\CourseLesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'date' => 'required|after:today|date_format:Y-m-d H:i:s',
            'address' => 'required|min:1|max:200',
        ];
    }
}
