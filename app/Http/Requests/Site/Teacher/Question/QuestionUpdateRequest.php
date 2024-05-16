<?php

namespace App\Http\Requests\Site\Teacher\Question;

use App\Enums\Question\QuestionStatus;
use App\Rules\InEnumRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;


class QuestionUpdateRequest extends FormRequest
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
        $id = $this->route('question')->id;
        return [
            'question' => ['required', 'string', 'max:255'],
            // 'score' => ['numeric', 'between:1,99'],
            'is_testing' => ['required', 'boolean'],
            'status' => [
                'required',
                new InEnumRule(QuestionStatus::getKeys())
            ],
            // 'type' => [
            //     'required',
            //     new InEnumRule(QuestionType::getKeys())
            // ],
            'answers_add' => ['array'],
            'answers_add.*.answer' => ['required', 'string', 'max:255'],
            'answers_add.*.is_correct' => ['required', 'boolean'],

            'answers_update' => ['array'],
            'answers_update.*.answer' => ['required', 'string', 'max:255'],
            'answers_update.*.id' => [
                'required',
                'max:255',
                'exists:answers,id,question_id,' . $id
            ],
            'answers_update.*.is_correct' => ['required', 'boolean'],

            'answers_delete' => ['array'],
            'answers_delete.*' => ['distinct', 'exists:answers,id,question_id,' . $id]
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $this->validated();
                $answers = collect([...$validated['answers_update'], ...$validated['answers_add']]);
                if($answers->unique('answer')->count()!== $answers->count()){
                    $validator->errors()->add(
                        'answer',
                        __('alert.answer.duplicate')
                    );
                }
            }
        ];
    }
}
