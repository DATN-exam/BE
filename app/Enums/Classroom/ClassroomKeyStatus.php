<?php

namespace App\Enums\Classroom;

use App\Traits\EnumToArray;

enum ClassroomKeyStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case INACTIVE = 2;
}
