<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\ApplicationMeetingHistory;

use App\Models\ApplicationMeetingHistory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateApplicationMeetingHistoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isMediator();
    }

    public function rules(): array
    {

        $applicationMeetingHistory = new ApplicationMeetingHistory();
        $date = $applicationMeetingHistory
            ->where('application_id', $this->route()->parameter('application'))
            ->first();
        $date = $date['created_at']->format('Y-m-d');
        return [
            'date' => [
                'nullable',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($date) {
                    if (strtotime($value) > strtotime($date)) {
                        $fail('The ' . $attribute . ' must be the creation date or earlier than the creation date');
                    }
                },
            ],
            'address' => 'nullable|string',
            'information' => 'nullable|string',
            'planning' => 'nullable|string',
            'attachments' => 'array|min:1|max:10',
            'attachments.*' => 'required|mimes:pdf,xlx,csv,docx,doc,jpg,png,mp3,mp4|max:2048',
            'deletedAttachmentsIds' => 'array',
            'deletedAttachmentsIds.*' => 'nullable|exists:application_attachments,id',
        ];
    }
}
