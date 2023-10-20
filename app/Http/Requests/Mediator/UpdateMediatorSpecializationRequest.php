<?php
declare(strict_types=1);

namespace App\Http\Requests\Mediator;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediatorSpecializationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isMediator() || $user->isAdmin();
    }

    public function rules(): array
    {
        return [
            'specialization' => 'required|string',
            'id' => 'required|integer|exists:users,id',
        ];
    }
}
