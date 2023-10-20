<?php
declare(strict_types=1);

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use Illuminate\Foundation\Http\FormRequest;

class GetAllApplicationUpcomingMeetingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->isMediator();
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
