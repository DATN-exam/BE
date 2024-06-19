<?php

namespace App\Enums\Exam;

use App\Traits\EnumToArray;

enum ExamStatus: int
{
    use EnumToArray;

    case UPCOMING = 1;
    case HAPPENING = 2;
    case HAPPENED = 3;
}
