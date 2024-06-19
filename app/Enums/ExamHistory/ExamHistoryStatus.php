<?php

namespace App\Enums\ExamHistory;

use App\Traits\EnumToArray;

enum ExamHistoryStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case DONE = 2;
}
