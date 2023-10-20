<?php
declare(strict_types=1);

namespace App\Http\Requests\Mediator;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediatorInstitutionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isMediator() || $user->isAdmin();
    }

    public function rules(): array
    {
        return [
            'mediator_company_id' => 'required',
            'id' => 'required|integer|exists:users,id',
        ];
    }
}
