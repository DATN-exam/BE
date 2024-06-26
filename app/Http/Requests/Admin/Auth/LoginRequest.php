<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'exists:users,email', 'min:5', 'max:100'],
            'password' => ['required', 'min:5', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'email.required'=>'Bạn phải nhập email',
            'email.email'=>'Phải là email',
            'email.exists'=>'Email không tồn tại',
        ];
    }
}
