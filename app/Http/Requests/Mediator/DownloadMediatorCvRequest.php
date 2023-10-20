<?php

namespace App\Http\Requests\Mediator;

use Illuminate\Foundation\Http\FormRequest;

class DownloadMediatorCvRequest extends FormRequest
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
