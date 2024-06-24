<?php

namespace App\Http\Requests\Site\Auth;

use App\Rules\AgeCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'first_name' => ['string', 'max:100'],
            'last_name' => ['string', 'max:100'],
            'dob' => [
                'date',
                'date_format:' . config('define.date_format'),
                new AgeCheckRule('dob', config('define.specified_age'))
            ],
            'avatar' => ['max:1024', 'image']
        ];
    }
}
