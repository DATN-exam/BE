<?php

namespace App\Enums\Question;

use App\Traits\EnumToArray;

enum SetQuestionStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case BLOCK = 2;
    case BLOCK_ADMIN = 3;
}
