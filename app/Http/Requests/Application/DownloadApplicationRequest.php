<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use function auth;

class DownloadApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {

// TODO add permission
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
