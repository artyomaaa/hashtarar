<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCourseRequest extends FormRequest
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
