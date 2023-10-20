<?php

declare(strict_types=1);


namespace App\Http\Requests\Mediator;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediatorAttachmentsRequest extends FormRequest
{
    public function authorize(): bool
    {

        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:users,id',
            'attachments' => 'array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'deletedAttachmentsIds' => 'array',
            'deletedAttachmentsIds.*' => 'required|exists:mediator_attachments,id',
        ];
    }
}
