<?php
declare(strict_types=1);

namespace App\Http\Requests\Application\ApplicationComment;

use Illuminate\Foundation\Http\FormRequest;

class GetApplicationCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
