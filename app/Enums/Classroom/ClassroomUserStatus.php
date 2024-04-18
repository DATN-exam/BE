<?php

namespace App\Enums\Classroom;

use App\Traits\EnumToArray;

enum ClassroomUserStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case BLOCK = 2;
}
