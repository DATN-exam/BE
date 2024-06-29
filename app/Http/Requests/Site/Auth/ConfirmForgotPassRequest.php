<?php

namespace App\Http\Requests\Site\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmForgotPassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:5', 'max:100'],
            'confirm_password' => ['required', 'string', 'same:new_password'],
        ];
    }
}
