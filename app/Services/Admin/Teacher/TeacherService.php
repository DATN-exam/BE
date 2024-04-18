<?php

namespace App\Services\Admin\Teacher;

use App\Enums\User\UserStatus;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class TeacherService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo
    ) {
        //
    }

    public function paginate()
    {
        return $this->userRepo->paginateTeacher($this->data);
    }

    public function block(User $teacher)
    {
        DB::beginTransaction();
        try {
            $this->userRepo->update($teacher, [
                'status' => UserStatus::ADMIN_BLOCK
            ]);
            //Send mail block to teacher
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function active(User $teacher)
    {
        DB::beginTransaction();
        try {
            $this->userRepo->update($teacher, [
                'status' => UserStatus::ACTIVE
            ]);
            //Send mail active to teacher
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
