<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class InEnumRule implements ValidationRule
{

    public function __construct(protected array $arr)
    {
        //
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array(Str::upper($value), $this->arr)) {
            $fail('validation.not_in')->translate([
                'attribute' => $attribute
            ]);
        }
    }
}
