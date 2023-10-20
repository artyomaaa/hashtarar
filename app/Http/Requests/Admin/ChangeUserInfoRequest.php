<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:users,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|integer',
            'password' => 'nullable|string',
            'other_means' => 'nullable|string',
        ];
    }
}
