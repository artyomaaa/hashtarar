<?php

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use App\Enums\ApplicationMeetingStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationUpcomingMeetingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('update', $this->route('application'));
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(
                [
                    ApplicationMeetingStatuses::CONFIRMED->value,
                    ApplicationMeetingStatuses::REJECTED->value
                ])
            ],
        ];
    }
}
