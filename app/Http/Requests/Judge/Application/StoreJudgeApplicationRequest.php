<?php

namespace App\Http\Requests\Judge\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreJudgeApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_type_id' => 'required|exists:case_types,id',
            'attachments' => 'required|array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'demand_letter' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
        ];
    }
}
