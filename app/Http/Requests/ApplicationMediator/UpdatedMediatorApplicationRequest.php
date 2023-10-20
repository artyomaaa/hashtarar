<?php
declare(strict_types=1);

namespace App\Http\Requests\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class UpdatedMediatorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isMediator();
    }

    public function rules(): array
    {
        return [
            'application_type_id' => 'nullable|string',
            'application_cause' => 'nullable|string|min:1|max:255'
        ];
    }
}
