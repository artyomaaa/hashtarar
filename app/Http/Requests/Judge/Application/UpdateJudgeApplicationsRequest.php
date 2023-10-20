<?php

namespace App\Http\Requests\Judge\Application;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJudgeApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isJudge();
    }

    public function rules(): array
    {

        return [
            'case_type_id' => 'required',
            'deletedAttachmentsIds' => 'array',
            'deletedAttachmentsIds.*' => 'nullable|exists:application_attachments,id',
            'attachments' => 'array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'demand_letter' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'deleteDemandLetterId' => 'nullable|exists:applications,id',
        ];
    }
}


