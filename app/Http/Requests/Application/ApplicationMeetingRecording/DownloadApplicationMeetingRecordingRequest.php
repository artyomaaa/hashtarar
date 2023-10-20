<?php

namespace App\Http\Requests\Application\ApplicationMeetingRecording;

use Illuminate\Foundation\Http\FormRequest;

class DownloadApplicationMeetingRecordingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
