<?php

namespace App\Observers;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use App\Models\TeacherRegistration;

class TeacherRegistrationObserver
{
    public function creating(TeacherRegistration $registration)
    {
        $registration->status = $registration->status
            ? $registration->status
            : TeacherRegistrationStatus::WAIT;
    }
}
