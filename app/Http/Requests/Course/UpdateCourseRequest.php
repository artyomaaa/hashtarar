<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|min:1|max:100',
            'duration_hours' => 'nullable|integer|min:1',
            'min_hours_for_exam' => 'nullable|integer|min:1',
            'start_date' => 'nullable|after:yesterday|date_format:Y-m-d',
        ];
    }
}
