<?php

namespace App\Repositories\TeacherRegistration;

use App\Models\TeacherRegistration;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface TeacherRegistrationRepositoryInterface extends RepositoryInterface
{
    public function checkHasBeenRegistration($userId);
}
