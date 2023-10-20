<?php

namespace App\Http\Requests\Mediator;

use App\Enums\CaseTypeGroups;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreMediatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'had_license_before' => 'required|boolean',
            'attachments' => 'required|array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'cv' => 'required|mimes:pdf,docx,doc,jpg,png|max:2048',
            'avatar' => 'required|mimes:jpg,png|max:2048',
        ];
    }
}
