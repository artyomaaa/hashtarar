<?php

namespace App\Http\Requests\Court;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
