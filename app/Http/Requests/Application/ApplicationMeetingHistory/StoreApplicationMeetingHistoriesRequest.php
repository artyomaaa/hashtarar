<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\ApplicationMeetingHistory;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationMeetingHistoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isMediator();
    }

    public function rules(): array
    {
        return [
            'date' => 'required|before_or_equal:today|date_format:Y-m-d',
            'address' => 'nullable|string',
            'information' => 'nullable|string',
            'planning' => 'nullable|string',
            'attachments' => 'required|array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:10240',
        ];
    }


    public function messages(): array
    {
        return [
            'date' => 'The date must be today or before today',
        ];
    }
}
