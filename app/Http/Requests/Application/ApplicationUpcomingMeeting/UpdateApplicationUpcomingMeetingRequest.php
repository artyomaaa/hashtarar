<?php

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use App\Enums\ApplicationMeetingTypes;
use App\Models\ApplicationUpcomingMeeting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateApplicationUpcomingMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('update', $this->route('application')) || $user->isMediator();
    }

    public function rules(): array
    {
        $applicationMeetingHistory = new ApplicationUpcomingMeeting();
        $date = $applicationMeetingHistory->select
        (
            DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as date"),
        )
            ->where('id', $this->route()->parameter('upcomingMeeting')->id)
            ->value('date');


        return [
            'type' => ['required', new Enum(ApplicationMeetingTypes::class)],
            'date' => [
                'nullable',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($date) {
                    if (strtotime($value) > strtotime($date)) {
                        $fail('The ' . $attribute . ' must be the creation date or earlier than the creation date');
                    }
                },
            ],
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
