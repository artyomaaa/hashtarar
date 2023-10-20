<?php
declare(strict_types=1);

namespace App\Http\Requests\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediatorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user->isMediator();
    }

    public function rules(): array
    {
        return [
            'application_type_id' => 'required|integer',
            'application_cause' => 'nullable|string|min:1|max:255'
        ];
    }
}
