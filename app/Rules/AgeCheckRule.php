<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AgeCheckRule implements ValidationRule
{

    public function __construct(protected $dob, protected $age)
    {
        //
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dob = Carbon::parse(request()->get($this->dob));
        $ageYearsAgo = Carbon::now()->subYears($this->age);

        if ($dob->gt($ageYearsAgo)) {
            $fail('validation.customers.date_dob')->translate(['age' => $this->age]);
        }
    }
}
