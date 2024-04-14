<?php

namespace App\Enums\User;

use App\Traits\EnumToArray;

enum UserStatus: string
{
    use EnumToArray;

    case BLOCK = 'block';
    case ADMIN_BLOCK = 'admin_block';
    case ACTIVE = 'active';
    case WAIT_VERIFY = 'wait_verify';
}
