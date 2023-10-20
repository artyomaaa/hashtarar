<?php

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use App\Enums\ApplicationMeetingStatuses;
use App\Enums\ApplicationMeetingTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreApplicationUpcomingMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isMediator();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(ApplicationMeetingTypes::class)],
            'date' => 'required|after:today|date_format:Y-m-d',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s',
            'code' => 'string|min:1|max:100',
            'url' => [
                Rule::requiredIf($this->request->get('type') === ApplicationMeetingTypes::ONLINE->value),
                'url'
            ],
            'address' => [
                Rule::requiredIf($this->request->get('type') === ApplicationMeetingTypes::OFFLINE->value),
                'min:1',
                'max:200'
            ]
        ];
    }
}
