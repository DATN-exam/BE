<?php

namespace App\Enums\Question;

use App\Traits\EnumToArray;

enum QuestionStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case BLOCK = 2;
}
