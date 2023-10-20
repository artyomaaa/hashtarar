<?php

namespace App\Http\Requests\Judge\Application;

use Illuminate\Foundation\Http\FormRequest;

class DestroyJudgeApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}


