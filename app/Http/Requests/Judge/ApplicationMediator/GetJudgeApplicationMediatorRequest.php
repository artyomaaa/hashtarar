<?php

declare(strict_types=1);

namespace App\Http\Requests\Judge\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class GetJudgeApplicationMediatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isJudge() || auth()->user()->isMediator();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
