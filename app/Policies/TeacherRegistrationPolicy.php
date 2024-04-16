<?php

namespace App\Policies;

use App\Models\TeacherRegistration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeacherRegistrationPolicy
{
    public function confirm(User $user, TeacherRegistration $teacherRegistration)
    {
        return $teacherRegistration->canCofirm();
    }

    public function deny(User $user, TeacherRegistration $teacherRegistration)
    {
        return $teacherRegistration->canCofirm();
    }
}
