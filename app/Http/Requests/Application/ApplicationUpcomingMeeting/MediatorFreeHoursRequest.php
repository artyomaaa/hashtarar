<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\ApplicationUpcomingMeeting;

use Illuminate\Foundation\Http\FormRequest;

class MediatorFreeHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|after:yesterday|date_format:Y-m-d',
        ];
    }
}
