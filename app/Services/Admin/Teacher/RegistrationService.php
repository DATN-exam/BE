<?php

namespace App\Services\Admin\Teacher;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use App\Enums\User\UserRole;
use App\Events\Admin\Teacher\CofirmRegistrationEvent;
use App\Models\TeacherRegistration;
use App\Repositories\TeacherRegistration\TeacherRegistrationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class RegistrationService extends BaseService
{
    public function __construct(
        protected TeacherRegistrationRepositoryInterface $registrationRepo,
        protected UserRepositoryInterface $userRepo
    ) {
        //
    }

    public function confirmRegistration(TeacherRegistration $teacherRegistration)
    {
        DB::beginTransaction();
        try {
            $this->registrationRepo->update($teacherRegistration->id, [
                'status' => TeacherRegistrationStatus::ACCEPT,
                'employee_cofirm_id' => auth('api')->user()->id,
            ]);
            $this->userRepo->update($teacherRegistration->user_id, [
                'role' => UserRole::TEACHER
            ]);
            event(new CofirmRegistrationEvent($teacherRegistration));
            DB::commit();
            return;
        } catch (Throwable $e) {
            throw $e;
            DB::rollBack();
        }
    }
}
