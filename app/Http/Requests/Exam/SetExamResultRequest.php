<?php

declare(strict_types=1);

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class SetExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'examResult' => 'required|array',
            'examResult*.course_id' => 'required|integer',
            'examResult*.user_id' => 'required|integer',
            'qualifications' => 'required|array|min:1',
            'qualifications.*' => 'required|string|distinct|min:0',
        ];
    }
}
