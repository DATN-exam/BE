<?php

namespace App\Services\Site\Student;

use App\Enums\Classroom\ClassroomKeyStatus;
use App\Enums\Classroom\ClassroomStatus;
use App\Enums\Classroom\ClassroomStudentStatus;
use App\Models\ClassroomKey;
use App\Repositories\Classroom\ClassroomKeyRepositoryInterface;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Repositories\Classroom\ClassroomStudentRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClassroomService extends BaseService
{
    public function __construct(
        protected ClassroomRepositoryInterface $classroomRepo,
        protected ClassroomKeyRepositoryInterface $keyRepo,
        protected ClassroomStudentRepositoryInterface $classroomStudentRepo
    ) {
        //
    }

    public function join(ClassroomKey $classroomKey)
    {
        $student = auth('api')->user();
        if (
            $classroomKey->remaining < 1
            || $classroomKey->status == ClassroomKeyStatus::INACTIVE
            || $classroomKey->expired < getToday()
        ) {
            return throw new \Exception(__('alert.classroom.key.invalid'));
        }
        $classroomKey->load('classroom');
        if ($classroomKey->classroom->teacher_id === $student->id) {
            return throw new \Exception(__('alert.classroom.key.not'));
        }
        $classroomStudent = $this->classroomStudentRepo
            ->getByClassroomStudent($classroomKey->classroom_id, $student->id);
        if ($classroomStudent && $classroomStudent->status === ClassroomStudentStatus::ACTIVE) {
            return throw new \Exception(__('alert.classroom.key.actived'));
        }
        if ($classroomStudent && $classroomStudent->status === ClassroomStudentStatus::BLOCK) {
            return throw new \Exception(__('alert.classroom.student.blocked'));
        }
        DB::beginTransaction();
        try {
            $this->keyRepo->decrement($classroomKey);
            $this->classroomStudentRepo->addStudent($classroomKey->classroom_id, $student, $classroomKey->key);
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function paginate()
    {
        $student = auth('api')->user();
        return $this->classroomRepo->paginateStudent($this->data, $student->id);
    }
}
