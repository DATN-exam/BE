<?php

namespace App\Enums\Classroom;

use App\Traits\EnumToArray;

enum ClassroomStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case BLOCK = 2;
    case ADMIN_BLOCK = 3;
}
