<?php

declare(strict_types=1);

namespace App\Http\Requests\Judge\ApplicationMediator;

use Illuminate\Foundation\Http\FormRequest;

class StoreJudgeApplicationMediatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isJudge();
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
