<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AnswersRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $countAnswerCorrect = 0;
        collect($value)->each(function ($answer) use (&$countAnswerCorrect) {
            if ($answer['is_correct'] == true) {
                $countAnswerCorrect++;
            }
        });
        if ($countAnswerCorrect > 1) {
            $fail('validation.customers.answer.one_correct');
        }
        if ($countAnswerCorrect == 0) {
            $fail('validation.customers.answer.must_correct');
        }
    }
}
