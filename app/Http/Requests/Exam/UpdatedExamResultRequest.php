<?php

declare(strict_types=1);

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdatedExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'examResult' => 'required|array',
            'examResult*.result_id' => 'required|integer',
            'examResult*.exam_result' => 'required|integer',
            'qualifications' => 'nullable|array|min:1',
            'qualifications.*' => 'nullable|string|distinct|min:0',
        ];
    }
}
