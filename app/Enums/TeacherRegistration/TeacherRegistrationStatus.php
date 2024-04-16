<?php

namespace App\Enums\TeacherRegistration;

use App\Traits\EnumToArray;

enum TeacherRegistrationStatus: int
{
    use EnumToArray;

    case WAIT = 1;
    case ACCEPT = 2;
    case DENY = 3;
    case CANCEL = 4;
}
