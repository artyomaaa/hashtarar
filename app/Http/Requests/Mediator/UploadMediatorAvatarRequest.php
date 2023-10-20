<?php
declare(strict_types=1);

namespace App\Http\Requests\Mediator;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediatorAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isMediator() || $user->isAdmin();
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required',
            'id' => 'required|integer|exists:users,id',
        ];
    }
}
