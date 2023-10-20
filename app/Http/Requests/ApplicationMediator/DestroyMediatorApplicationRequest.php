<?php
declare(strict_types=1);

namespace App\Http\Requests\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class DestroyMediatorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isMediator();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
