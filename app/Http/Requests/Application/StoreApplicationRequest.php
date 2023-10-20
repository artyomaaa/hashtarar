<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'case_type_id' => 'required|exists:case_types,id',
            'attachments' => 'required|array|min:1|max:10',
            'citizen_id' => 'required|exists:users,id',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
        ];
    }
}
