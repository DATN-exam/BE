<?php

namespace App\Services\Admin\Student;

use App\Enums\User\UserStatus;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudentService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo
    ) {
        //
    }

    public function paginate()
    {
        return $this->userRepo->paginateStudent($this->data);
    }

    public function show(User $student)
    {
        return $student;
    }

    public function block(User $student)
    {
        DB::beginTransaction();
        try {
            $this->userRepo->update($student->id, [
                'status' => UserStatus::ADMIN_BLOCK
            ]);
            //Send mail block to user
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function active(User $student)
    {
        DB::beginTransaction();
        try {
            $this->userRepo->update($student->id, [
                'status' => UserStatus::ACTIVE
            ]);
            //Send mail active to user
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
