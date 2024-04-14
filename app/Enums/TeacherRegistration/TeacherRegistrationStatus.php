<?php

namespace App\Enums\TeacherRegistration;

use App\Traits\EnumToArray;

enum TeacherRegistrationStatus: string
{
    use EnumToArray;

    case WAIT = 'wait';
    case ACCEPT = 'accept';
    case DENY = 'deny';
    case CANCEL = 'cancel';
}
