<?php

declare(strict_types=1);

namespace App\Http\Requests\Course\CourseMediator;

use Illuminate\Foundation\Http\FormRequest;

class SetMediatorsToCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin() || $user->isMediator();
    }

    public function rules(): array
    {
        return [
            "mediator_id"=>"required|integer|exists:users,id"
        ];
    }
}
