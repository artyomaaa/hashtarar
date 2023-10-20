<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\ApplicationMeetingHistory;

use Illuminate\Foundation\Http\FormRequest;

class DestroyApplicationMeetingHistoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application')) || $user->isMediator();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
