<?php

namespace App\Http\Requests\Site\Teacher\Question;

use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use App\Rules\AnswersRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:255'],
            'score' => ['numeric', 'between:1,99'],
            'is_testing' => ['required', 'boolean'],
            'status' => ['required', Rule::in(QuestionStatus::getKeys())],
            'type' => ['required', Rule::in(QuestionType::getKeys())],
            'answers' => ['required', 'array', 'min:1', new AnswersRule()],
            'answers.*.answer' => ['required', 'string', 'max:255'],
            'answers.*.is_correct' => ['required', 'boolean'],
        ];
    }
}
