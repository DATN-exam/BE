<?php

namespace App\Http\Requests\Site\Teacher\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'is_show_result' => ['required', 'boolean'],
            'number_question_easy' => ['numeric'],
            'number_question_medium' => ['numeric'],
            'number_question_hard' => ['numeric'],
            'set_question_id' => ['required', 'exists:set_questions,id'],
            'start_date' => ['required', 'date_format:' . config('define.datime_format')],
            'end_date' => ['required', 'date_format:' . config('define.datime_format')],
            'working_time' => ['required', 'date_format:H:i:s'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
