<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class DestroyApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('delete', $this->route('application')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
           //
        ];
    }
}
