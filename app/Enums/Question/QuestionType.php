<?php

namespace App\Enums\Question;

use App\Traits\EnumToArray;

enum QuestionType: int
{
    use EnumToArray;

    case MULTIPLE = 1;
    case ESSAY = 2;
}
