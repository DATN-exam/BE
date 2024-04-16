<?php

namespace App\Enums\User;

use App\Traits\EnumToArray;

enum UserRole: int
{
    use EnumToArray;

    case STUDENT = 1;
    case TEACHER = 2;
    case ADMIN = 3;
}
