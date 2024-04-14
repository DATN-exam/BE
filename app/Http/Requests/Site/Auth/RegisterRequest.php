<?php

namespace App\Http\Requests\Site\Auth;

use App\Rules\AgeCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email', 'min:5', 'max:100'],
            'password' => ['required', 'min:5', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'dob' => [
                'required',
                'date',
                'date_format:' . config('define.date_format'),
                new AgeCheckRule('dob', config('define.specified_age'))
            ],
        ];
    }
}
