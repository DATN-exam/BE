<?php

namespace App\Enums\User;

use App\Traits\EnumToArray;

enum UserStatus: int
{
    use EnumToArray;

    case WAIT_VERIFY = 1;
    case ACTIVE = 2;
    case BLOCK = 3;
    case ADMIN_BLOCK = 4;
}
