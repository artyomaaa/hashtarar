<?php

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use Illuminate\Foundation\Http\FormRequest;

class GetApplicationUpcomingMeetingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isEmployeeOrAdmin();
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
