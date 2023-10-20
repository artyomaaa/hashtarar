<?php

namespace App\Http\Requests\Application\ApplicationComment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreApplicationCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user->can('view', $this->route('application'));
    }

    public function rules(): array
    {
        return [
            'message' => 'required|min:1|max:1000',
        ];
    }
}
