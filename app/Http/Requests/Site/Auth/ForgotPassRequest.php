<?php

namespace App\Http\Requests\Site\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPassRequest extends FormRequest
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
            'email' => ['required', 'exists:users,email']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.exists' => 'Email không khớp với bất kỳ tài khoản nào',
        ];
    }
}
