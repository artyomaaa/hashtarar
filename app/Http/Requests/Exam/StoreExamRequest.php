<?php
declare(strict_types=1);

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'exam_date' => 'required|date_format:Y-m-d H:i:s|after:today',
            'exam_place' => 'required|string',
        ];
    }
}
