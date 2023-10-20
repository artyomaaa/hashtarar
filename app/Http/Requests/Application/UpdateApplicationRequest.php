<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('update', $this->route('application')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'case_type_id' => 'required|exists:case_types,id',
            'deletedAttachmentsIds' => 'array',
            'deletedAttachmentsIds.*' => 'nullable|exists:application_attachments,id',
            'attachments' => 'array|min:1|max:10',
            'attachments.*' => 'nullable|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'application' => 'nullable|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'deletedApplicationId' => 'nullable|exists:applications,id',
        ];
    }
}
