<?php

namespace App\Http\Requests\Citizen\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreCitizenApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_type_id' => 'required|exists:case_types,id',
            'application' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'attachments' => 'required|array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
        ];
    }
}
