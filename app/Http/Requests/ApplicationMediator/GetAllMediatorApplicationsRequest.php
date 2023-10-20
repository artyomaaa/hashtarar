<?php
declare(strict_types=1);

namespace App\Http\Requests\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class GetAllMediatorApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
