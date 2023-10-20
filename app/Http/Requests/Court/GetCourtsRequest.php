<?php

namespace App\Http\Requests\Court;

use Illuminate\Foundation\Http\FormRequest;

class GetCourtsRequest extends FormRequest
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
