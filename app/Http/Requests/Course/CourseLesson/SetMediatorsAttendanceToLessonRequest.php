<?php
declare(strict_types=1);

namespace App\Http\Requests\Course\CourseLesson;

use Illuminate\Foundation\Http\FormRequest;

class SetMediatorsAttendanceToLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin() || $user->isMediator();
    }

    public function rules(): array
    {
        return [
            'mediatorIds' => 'array',
            'mediatorIds.*' => 'required|exists:users,id',
        ];
    }
}
