<?php

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use App\Enums\ApplicationMeetingStatuses;
use App\Enums\ApplicationMeetingTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class DestroyApplicationUpcomingMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('update', $this->route('application')) && $user->isMediator();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
