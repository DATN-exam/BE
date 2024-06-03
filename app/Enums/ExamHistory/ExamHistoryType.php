<?php

namespace App\Enums\ExamHistory;

use App\Traits\EnumToArray;

enum ExamHistoryType: int
{
    use EnumToArray;

    case EXPERIMENT = 1;
    case TEST = 2;
}
