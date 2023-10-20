<?php

namespace App\Http\Requests\Application\ApplicationActivityLog;

use Illuminate\Foundation\Http\FormRequest;

class GetApplicationActivityLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
