<?php
declare(strict_types=1);

namespace App\Http\Requests\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class GetMediatorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isMediator() || auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
