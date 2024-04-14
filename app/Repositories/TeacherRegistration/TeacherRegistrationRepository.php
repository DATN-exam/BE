<?php

namespace App\Repositories\TeacherRegistration;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use App\Enums\User\UserStatus;
use App\Models\TeacherRegistration;
use App\Models\User;
use App\Repositories\BaseRepository;

class TeacherRegistrationRepository extends BaseRepository implements TeacherRegistrationRepositoryInterface
{
    public function getModel()
    {
        return TeacherRegistration::class;
    }

    public function checkHasBeenRegistration($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereIn('status', [
                TeacherRegistrationStatus::ACCEPT, TeacherRegistrationStatus::WAIT
            ])
            ->exists();
    }
}
