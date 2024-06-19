<?php

namespace App\Http\Requests\Site\Student\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAnswerRequest extends FormRequest
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
            'answer_id' => ['exists:answers,id'],
            'answer_text' => ['string', 'max:255'],
        ];
    }
}
