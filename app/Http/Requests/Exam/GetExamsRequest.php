<?php
declare(strict_types=1);

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class GetExamsRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO specify  permission
        return true;
        $user = auth()->user();
        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
