<?php

namespace App\Enums\Classroom;

use App\Traits\EnumToArray;

enum ClassroomStudentStatus: int
{
    use EnumToArray;

    case ACTIVE = 1;
    case BLOCK = 2;
}
