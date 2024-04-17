<?php

namespace App\Http\Requests\Admin\Student;

use App\Rules\AgeCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'dob' => [
                'required',
                'date',
                'date_format:' . config('define.date_format'),
                new AgeCheckRule('dob', config('define.specified_age'))
            ],
        ];
    }
}
