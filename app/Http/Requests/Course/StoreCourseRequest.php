<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:1|max:100',
            'duration_hours' => 'required|integer|min:1',
            'min_hours_for_exam' => 'required|integer|min:1|lte:duration_hours',
            'is_training' => ['required', 'boolean'],
            'start_date' => 'required|after:yesterday|date_format:Y-m-d',
        ];
    }
}
