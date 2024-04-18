<?php

namespace App\Services\Site\Teacher;

use App\Events\Site\Teacher\TeacherRegisterEvent;
use App\Models\User;
use App\Repositories\TeacherRegistration\TeacherRegistrationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;


class TeacherService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected TeacherRegistrationRepositoryInterface $teacherRegisRepo
    ) {
        //
    }

    public function register()
    {
        /**
         * @var User $user
         */
        $user = auth('api')->user();
        if (!$user->isStudent()) {
            throw new \Exception(__('alert.teacher.register.has_been'));
        }
        $checkUser = $this->teacherRegisRepo->checkHasBeenRegistration($user->id);
        if ($checkUser) {
            throw new \Exception(__('alert.teacher.register.has_been'));
        }
        $registration = $this->teacherRegisRepo->create([
            ...$this->data,
            'user_id' => $user->id,
        ]);
        event(new TeacherRegisterEvent());
        return $registration;
    }
}
