<?php

namespace App\Enums\Question;

use App\Traits\EnumToArray;

enum QuestionLevel: int
{
    use EnumToArray;

    case EASY = 1;
    case MEDIUM = 2;
    case HARD = 3;
}
